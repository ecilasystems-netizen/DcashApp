<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FlutterwaveWebhookController extends Controller
{

    public function handleFlutterwaveWebhook(Request $request)
    {
        // 1. Verify the webhook signature for security
        $secretHash = config('services.flutterwave.webhook_hash');
        $signature = $request->header('verif-hash');

        if (!$signature || ($signature !== $secretHash)) {
            Log::warning('Flutterwave Webhook: Invalid signature received.');
            abort(401, 'Invalid signature');
        }

        // 2. Get the payload from the request
        $payload = $request->all();
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? null;

        // 3. Check the event type and process accordingly
        if ($event === 'charge.completed' && strtolower($data['status'] ?? '') === 'successful') {
            $this->processDeposit($data);
        } elseif ($event === 'transfer.completed' && strtolower($data['status'] ?? '') === 'successful') {
            $this->processTransfer($data);
        }

        // 4. Acknowledge receipt with a 200 OK response
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Process a successful deposit.
     */
    private function processDeposit(array $data): void
    {
        $txRef = $data['tx_ref'] ?? null;
        $customerEmail = $data['customer']['email'] ?? null;
        $amount = $data['amount'] ?? 0;
        $txId = $data['id'] ?? null;

        if (!$txRef || !$customerEmail || $amount <= 0) {
            Log::warning('Flutterwave Webhook: Missing data for deposit.', $data);
            return;
        }

        // Prevent duplicate processing
        if (WalletTransaction::where('reference', $txId)->exists()) {
            Log::info("Flutterwave Webhook: Deposit {$txId} already processed.");
            return;
        }

        $user = User::where('email', $customerEmail)->first();
        if (!$user) {
            Log::warning("Flutterwave Webhook: User with email {$customerEmail} not found for deposit.");
            return;
        }

        DB::transaction(function () use ($txId, $user, $amount, $txRef, $data) {
            // Find or create the user's wallet
            $wallet = Wallet::where('user_id', $user->id)->first();

            $balanceBefore = $wallet->balance;
            $wallet->balance += $amount;
            $wallet->save();

            // Verify transaction with FlutterwaveService to fetch originator info
            $originatorName = null;
            $originatorBank = null;

            try {
                $flutterwaveService = app(FlutterwaveService::class);
                $verification = $flutterwaveService->verifyTransactionStatus($txId);


                if ($verification['success'] === true) {
                    $meta = $verification['data']['meta'] ?? [];
                    $originatorName = $meta['originatorname'] ?? null;
                    $originatorBank = $meta['bankname'] ?? null;
                }
            } catch (\Throwable $e) {
                Log::warning("Flutterwave Webhook: Transaction verification failed for {$txId}.",
                    ['error' => $e->getMessage()]);
            }

            // Create a new wallet transaction record
            WalletTransaction::create([
                'reference' => $txId,
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'direction' => 'credit',
                'type' => 'deposit',
                'amount' => $amount,
                'charge' => 0,
                'description' => 'Wallet deposit via Flutterwave',
                'status' => 'completed',
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
                'metadata' => [
                    'originator_name' => $originatorName,
                    'originator_bank' => $originatorBank,
                    'txRef' => $txRef,
                    'created_at' => $data['created_at'] ?? null
                ]
            ]);

            Log::info("Flutterwave Webhook: Deposit {$txId} processed successfully for user {$user->id}.");
        });


    }

    /**
     * Process a successful transfer.
     */
    private function processTransfer(array $data): void
    {
        $reference = $data['reference'] ?? null;

        if (!$reference) {
            Log::warning('Flutterwave Webhook: No transaction reference found for transfer.');
            return;
        }

        $transaction = WalletTransaction::where('reference', $reference)->first();

        if ($transaction) {
            if ($transaction->status !== 'completed') {
                $transaction->status = 'completed';
//
                $transaction->save();
                Log::info("Flutterwave Webhook: Transfer {$reference} updated to successful.");
            } else {
                Log::info("Flutterwave Webhook: Transfer {$reference} was already processed.");
            }
        } else {
            Log::warning("Flutterwave Webhook: Transfer with reference {$reference} not found.");
        }
    }
}
