<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\WebhookValidationException;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SafeHavenWebhookController extends Controller
{
    /**
     * SafeHaven's production IP addresses for webhook verification.
     * Set SAFEHAVEN_WEBHOOK_IPS=* in .env to accept from all IPs (not recommended for production)
     */
    private const ALLOWED_IPS = [
        '18.201.210.116',
        '196.6.103.73',
        '196.6.103.74',
    ];

    /**
     * Handle incoming SafeHaven webhook events.
     */
    public function handle(Request $request): JsonResponse
    {
        try {
            // Log incoming webhook
            Log::info('SafeHaven Webhook: Received webhook request', [
                'ip' => $request->ip(),
                'payload' => $request->all(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // 1. Verify webhook source (IP whitelisting)
            $this->verifyWebhookSource($request);

            // 2. Get the payload
            $payload = $request->all();
            $type = $payload['type'] ?? null;
            $data = $payload['data'] ?? null;

            if (!$type || !$data) {
                Log::warning('SafeHaven Webhook: Missing type or data.', $payload);
                return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
            }

            Log::info("SafeHaven Webhook: Processing {$type} event.", [
                'type' => $type,
                'session_id' => $data['sessionId'] ?? null,
                'amount' => $data['amount'] ?? null
            ]);

            // 3. Process based on transaction type
            if ($type === 'transfer') {
                $this->handleTransfer($data);
            } else {
                Log::info("SafeHaven Webhook: Unhandled type: {$type}");
            }

            // 4. Acknowledge receipt
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook processed successfully'
            ], 200);

        } catch (WebhookValidationException $e) {
            Log::error('SafeHaven Webhook: Validation failed.', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);

        } catch (\Throwable $e) {
            Log::error('SafeHaven Webhook: Processing failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);
            return response()->json(['status' => 'error', 'message' => 'Processing failed'], 500);
        }
    }

    /**
     * Verify webhook source using IP whitelisting.
     */
    private function verifyWebhookSource(Request $request): void
    {
        $requestIp = $request->ip();
        $allowedIps = config('safehaven.webhook_allowed_ips', self::ALLOWED_IPS);

        // Skip verification in local development
        if (app()->environment('local')) {
            Log::info("SafeHaven Webhook: IP verification skipped in local environment.", ['ip' => $requestIp]);
            return;
        }

        // Check if wildcard is enabled (accept all IPs)
        if (in_array('*', $allowedIps, true)) {
            Log::info("SafeHaven Webhook: Wildcard IP verification enabled.", ['ip' => $requestIp]);
            return;
        }

        // Verify against whitelist
        if (!in_array($requestIp, $allowedIps, true)) {
            throw new WebhookValidationException("Unauthorized IP address: {$requestIp}");
        }

        Log::info("SafeHaven Webhook: IP verification passed.", ['ip' => $requestIp]);
    }

    /**
     * Handle transfer webhook - processes both inward and outward transfers.
     */
    private function handleTransfer(array $data): void
    {
        $transferType = $data['type'] ?? null;
        $sessionId = $data['sessionId'] ?? null;
        $amount = $data['amount'] ?? 0;
        $responseCode = $data['responseCode'] ?? null;
        $status = $data['status'] ?? null;

        if (!$sessionId) {
            Log::warning('SafeHaven Webhook: Transfer missing sessionId.', $data);
            return;
        }

        Log::info("SafeHaven Webhook: Processing {$transferType} transfer.", [
            'session_id' => $sessionId,
            'amount' => $amount,
            'status' => $status,
            'response_code' => $responseCode
        ]);

        // Process based on transfer direction
        match ($transferType) {
            'Inwards' => $this->handleInwardTransfer($data),
            /**   'Outwards' => $this->handleOutwardTransfer($data), */
            default => Log::warning("SafeHaven Webhook: Unknown transfer type: {$transferType}", $data)
        };
    }

    /**
     * Handle inward transfer (deposit) - customer receives money.
     */
    private function handleInwardTransfer(array $data): void
    {
        $creditAccountNumber = $data['creditAccountNumber'] ?? null;
        $amount = $data['amount'] ?? 0;
        $sessionId = $data['sessionId'] ?? null;
        $narration = $data['narration'] ?? 'Deposit via bank transfer';
        $status = strtolower($data['status'] ?? '');
        $responseCode = $data['responseCode'] ?? null;

        if (!$creditAccountNumber || !$sessionId || $amount <= 0) {
            Log::warning('SafeHaven Webhook: Invalid inward transfer data.', $data);
            return;
        }

        // Only process completed transactions
        if ($status !== 'completed' && $responseCode !== '00') {
            Log::info("SafeHaven Webhook: Ignoring non-completed inward transfer.", [
                'session_id' => $sessionId,
                'status' => $status,
                'response_code' => $responseCode
            ]);
            return;
        }

        // Convert amount from Naira to Kobo (smallest unit)
//        $amountInKobo = (int) round($amount * 100);

        // Prevent duplicate processing
        if (WalletTransaction::where('reference', $sessionId)->exists()) {
            Log::info("SafeHaven Webhook: Inward transfer {$sessionId} already processed.");
            return;
        }

        // Find user by virtual account number
        $user = User::whereHas('virtualBankAccount', function ($query) use ($creditAccountNumber) {
            $query->where('account_number', $creditAccountNumber)
                ->where('provider', 'safehaven')
                ->where('is_active', true);
        })->first();

        if (!$user) {
            Log::warning("SafeHaven Webhook: User not found for account {$creditAccountNumber}", [
                'account_number' => $creditAccountNumber,
                'session_id' => $sessionId
            ]);
            return;
        }

        DB::transaction(function () use ($user, $amount, $sessionId, $narration, $data) {
            // Get user wallet - must already exist
            $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

            $balanceBefore = $wallet->balance;
            $wallet->balance += $amount;
            $wallet->save();

            // Create transaction record
            WalletTransaction::create([
                'reference' => $sessionId,
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'direction' => 'credit',
                'type' => 'deposit',
                'amount' => $amount,
                'charge' => 0,
                'description' => $narration,
                'status' => 'completed',
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
                'metadata' => [
                    'provider' => 'safehaven',
                    'transfer_type' => 'inward',
                    'session_id' => $sessionId,
                    'payment_reference' => $data['paymentReference'] ?? null,
                    'name_enquiry_reference' => $data['nameEnquiryReference'] ?? null,
                    'credit_account_number' => $data['creditAccountNumber'] ?? null,
                    'credit_account_name' => $data['creditAccountName'] ?? null,
                    'debit_account_number' => $data['debitAccountNumber'] ?? null,
                    'debit_account_name' => $data['debitAccountName'] ?? null,
                    'provider_channel' => $data['providerChannel'] ?? null,
                    'transaction_location' => $data['transactionLocation'] ?? null,
                    'response_code' => $data['responseCode'] ?? null,
                    'response_message' => $data['responseMessage'] ?? null,
                    'created_at' => $data['createdAt'] ?? null,
                    'approved_at' => $data['approvedAt'] ?? null,
                    'webhook_received_at' => now()->toDateTimeString(),
                    'originator_name' => $data['debitAccountName'] ?? null,
                    'originator_bank' => null,
                    'txRef' => $sessionId,
                ]
            ]);

            Log::info("SafeHaven Webhook: Inward transfer processed successfully.", [
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance
            ]);
        });
    }

    /**
     * Handle outward transfer (withdrawal) - update status of existing transaction.
     */
    private function handleOutwardTransfer(array $data): void
    {
        $sessionId = $data['sessionId'] ?? null;
        $responseCode = $data['responseCode'] ?? null;
        $status = strtolower($data['status'] ?? '');

        if (!$sessionId) {
            Log::warning('SafeHaven Webhook: Outward transfer missing sessionId.', $data);
            return;
        }

        $transaction = WalletTransaction::where('reference', $sessionId)->first();

        if (!$transaction) {
            Log::warning("SafeHaven Webhook: Outward transfer transaction not found.", [
                'session_id' => $sessionId,
                'response_code' => $responseCode
            ]);
            return;
        }

        // Map SafeHaven status to our transaction status
        $newStatus = match ($status) {
            'completed' => 'completed',
            'pending' => 'pending',
            'failed', 'reversed' => 'failed',
            default => $responseCode === '00' ? 'completed' : 'failed'
        };

        if ($transaction->status !== $newStatus) {
            DB::transaction(function () use ($transaction, $newStatus, $data, $sessionId) {
                $oldStatus = $transaction->status;
                $transaction->status = $newStatus;

                // Update metadata
                $metadata = $transaction->metadata ?? [];
                $metadata['webhook_update'] = [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'response_code' => $data['responseCode'] ?? null,
                    'response_message' => $data['responseMessage'] ?? null,
                    'is_reversed' => $data['isReversed'] ?? false,
                    'reversal_reference' => $data['reversalReference'] ?? null,
                    'updated_at' => now()->toDateTimeString()
                ];
                $transaction->metadata = $metadata;
                $transaction->save();

                // If transfer failed or reversed, refund the wallet
                if ($newStatus === 'failed' && $transaction->direction === 'debit') {
                    $wallet = $transaction->wallet;
                    $refundAmount = $transaction->amount + $transaction->charge;
                    $wallet->balance += $refundAmount;
                    $wallet->save();

                    Log::info("SafeHaven Webhook: Refunded failed outward transfer.", [
                        'session_id' => $sessionId,
                        'amount' => $transaction->amount,
                        'charge' => $transaction->charge,
                        'total_refund' => $refundAmount
                    ]);
                }

                Log::info("SafeHaven Webhook: Outward transfer status updated.", [
                    'session_id' => $sessionId,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            });
        }
    }
}
