<?php

declare(strict_types=1);

namespace App\Jobs\SafeHaven;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\SafeHavenApi\TransfersService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SafeHavenProcessBankTransferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 45;
    public int $backoff = 10;

    public function __construct(
        public string $transactionReference,
        public int $userId,
        public string $nameEnquiryReference,
        public string $beneficiaryBankCode,
        public string $beneficiaryAccountNumber,
        public int $amount,
        public string $narration
    ) {}

    public function handle(TransfersService $transferService): void
    {
        Log::info('ProcessBankTransfer - starting', [
            'reference' => $this->transactionReference,
            'user_id' => $this->userId,
            'amount' => $this->amount
        ]);

        $transaction = WalletTransaction::where('reference', $this->transactionReference)->first();

        if (!$transaction) {
            Log::error('ProcessBankTransfer - transaction not found', [
                'reference' => $this->transactionReference
            ]);
            return;
        }

        if ($transaction->status !== 'pending') {
            Log::warning('ProcessBankTransfer - transaction already processed', [
                'reference' => $this->transactionReference,
                'status' => $transaction->status
            ]);
            return;
        }

        try {
            $transferData = [
                'nameEnquiryReference' => $this->nameEnquiryReference,
                'beneficiaryBankCode' => $this->beneficiaryBankCode,
                'debitAccountNumber' => config('safehaven.debit_account_number'),
                'beneficiaryAccountNumber' => $this->beneficiaryAccountNumber,
                'amount' => $this->amount,
                'narration' => $this->narration,
                'paymentReference' => $this->transactionReference,
            ];

            $response = $transferService->initiateTransfer($transferData);

            if (in_array($response['status'], [200, 201])) {
                $this->handleSuccessfulTransfer($transaction, $response);
            } else {
                $this->handleFailedTransfer($transaction, $response);
            }

        } catch (\Exception $e) {
            Log::error('ProcessBankTransfer - exception', [
                'reference' => $this->transactionReference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->handleFailedTransfer($transaction, [
                'json' => ['message' => $e->getMessage()]
            ]);

            throw $e;
        }
    }

    private function handleSuccessfulTransfer(WalletTransaction $transaction, array $response): void
    {
        DB::transaction(function () use ($transaction, $response) {
            $metadata = $transaction->metadata ?? [];
            $metadata['transfer_session_id'] = $response['json']['data']['sessionId'] ?? null;
            $metadata['transfer_status'] = $response['json']['data']['status'] ?? null;
            $metadata['transfer_date'] = now()->toDateTimeString();
            $metadata['completed_at'] = now()->toDateTimeString();

            $transaction->status = 'completed';
            $transaction->metadata = $metadata;
            $transaction->save();

            Log::info('ProcessBankTransfer - success', [
                'reference' => $this->transactionReference,
                'session_id' => $response['json']['data']['sessionId'] ?? null
            ]);

            // Send notification
            $user = User::find($this->userId);
            $user->notify(new \App\Notifications\SafeHaven\SafeHavenTransferCompletedNotification(
                $this->transactionReference,
                true,
                'Transfer completed successfully'
            ));
        });
    }

    private function handleFailedTransfer(WalletTransaction $transaction, array $response): void
    {
        DB::transaction(function () use ($transaction, $response) {
            $user = User::find($this->userId);
            $wallet = $user->wallet;

            // Refund the amount including transfer fee
            $refundAmount = $transaction->amount + $transaction->charge;

            DB::table('wallets')
                ->where('id', $wallet->id)
                ->increment('balance', $refundAmount);

            $metadata = $transaction->metadata ?? [];
            $metadata['failure_reason'] = $response['json']['message'] ?? 'Transfer failed';
            $metadata['failed_at'] = now()->toDateTimeString();

            $transaction->status = 'failed';
            $transaction->metadata = $metadata;
            $transaction->save();

            Log::error('ProcessBankTransfer - failed', [
                'reference' => $this->transactionReference,
                'error' => $response['json']['message'] ?? 'Unknown error',
                'refunded_amount' => $refundAmount
            ]);

            // Send failure notification
            $user->notify(new \App\Notifications\SafeHaven\SafeHavenTransferCompletedNotification(
                $this->transactionReference,
                false,
                $response['json']['message'] ?? 'Transfer failed'
            ));
        });
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessBankTransfer - job failed after retries', [
            'reference' => $this->transactionReference,
            'error' => $exception->getMessage()
        ]);

        $transaction = WalletTransaction::where('reference', $this->transactionReference)->first();

        if ($transaction && $transaction->status === 'pending') {
            $this->handleFailedTransfer($transaction, [
                'json' => ['message' => 'Transfer failed after maximum retries: ' . $exception->getMessage()]
            ]);
        }
    }
}
