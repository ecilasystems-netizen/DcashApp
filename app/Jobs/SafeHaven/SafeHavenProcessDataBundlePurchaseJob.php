<?php

declare(strict_types=1);

namespace App\Jobs\SafeHaven;

use App\Models\SafehavenDataBundle;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\SafeHavenApi\SafehavenDataBundleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SafeHavenProcessDataBundlePurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;
    public int $backoff = 5;

    public function __construct(
        public string $transactionReference,
        public int $userId,
        public string $phoneNumber,
        public int $bundleId,
        public string $serviceCategoryId,
        public int $amount
    ) {
    }

    public function handle(SafehavenDataBundleService $bundleService): void
    {
        $transaction = WalletTransaction::where('reference', $this->transactionReference)->first();

        if (!$transaction) {
            Log::error('ProcessDataBundlePurchase - transaction not found', [
                'reference' => $this->transactionReference
            ]);
            return;
        }

        if ($transaction->status !== 'pending') {
            Log::info('ProcessDataBundlePurchase - transaction already processed', [
                'reference' => $this->transactionReference,
                'status' => $transaction->status
            ]);
            return;
        }

        try {
            $bundle = SafehavenDataBundle::find($this->bundleId);

            if (!$bundle) {
                $this->handleFailedPurchase($transaction, [
                    'json' => ['message' => 'Bundle not found']
                ]);
                return;
            }

            $response = $bundleService->purchaseDataBundle(
                $this->phoneNumber,
                (string) $bundle->bundle_code,
                $this->serviceCategoryId,
                (float) $this->amount
            );

            if (in_array($response['status'], [200, 201])) {
                $this->handleSuccessfulPurchase($transaction, $response);
            } else {
                $this->handleFailedPurchase($transaction, $response);
            }

        } catch (\Exception $e) {
            Log::error('ProcessDataBundlePurchase - exception', [
                'reference' => $this->transactionReference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->handleFailedPurchase($transaction, [
                'json' => ['message' => $e->getMessage()]
            ]);

            throw $e;
        }
    }

    private function handleFailedPurchase(WalletTransaction $transaction, array $response): void
    {
        DB::transaction(function () use ($transaction, $response) {
            $user = User::find($this->userId);
            $wallet = $user->wallet;

            // Refund the amount
            DB::table('wallets')
                ->where('id', $wallet->id)
                ->increment('balance', $this->amount);

            $transaction->status = 'failed';
            $transaction->metadata = array_merge((array) $transaction->metadata, [
                'failure_reason' => $response['json']['message'] ?? 'Purchase failed',
                'api_response' => $response['json'] ?? null,
                'failed_at' => now()->toDateTimeString(),
                'refunded' => true
            ]);
            $transaction->save();

            Log::warning('ProcessDataBundlePurchase - failed and refunded', [
                'reference' => $this->transactionReference,
                'reason' => $response['json']['message'] ?? 'Unknown error',
                'refund_amount' => $this->amount
            ]);
        });
    }

    private function handleSuccessfulPurchase(WalletTransaction $transaction, array $response): void
    {
        DB::transaction(function () use ($transaction, $response) {
            $transaction->status = 'completed';
            $transaction->metadata = array_merge((array) $transaction->metadata, [
                'tx_ref' => $response['json']['data']['reference'] ?? null,
                'sf_ref' => $response['json']['data']['reference'] ?? null,
                'serviceCategoryId' => $response['json']['data']['serviceCategoryId'] ?? null,
                'api_response' => $response['json'] ?? null,
                'completed_at' => now()->toDateTimeString()
            ]);
            $transaction->save();

            Log::info('ProcessDataBundlePurchase - successful', [
                'reference' => $this->transactionReference,
                'sf_reference' => $response['json']['data']['reference'] ?? null
            ]);
        });
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessDataBundlePurchase - job failed after retries', [
            'reference' => $this->transactionReference,
            'error' => $exception->getMessage()
        ]);

        $transaction = WalletTransaction::where('reference', $this->transactionReference)->first();

        if ($transaction && $transaction->status === 'pending') {
            $this->handleFailedPurchase($transaction, [
                'json' => ['message' => 'Job failed after maximum retries: '.$exception->getMessage()]
            ]);
        }
    }
}
