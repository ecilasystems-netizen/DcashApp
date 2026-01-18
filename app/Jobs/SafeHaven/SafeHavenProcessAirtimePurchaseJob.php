<?php

namespace App\Jobs\SafeHaven;

use App\Models\SafehavenAirtimeProvider;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\SafeHavenApi\SafehavenBillsPaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SafeHavenProcessAirtimePurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public int $tries = 3;
    public int $timeout = 30;
    public int $backoff = 5;

    public function __construct(
        public string $transactionReference,
        public int $userId,
        public string $phoneNumber,
        public int $amount,
        public string $serviceCategoryId
    ) {}

    public function handle(SafehavenBillsPaymentService $billsService): void
    {


        $transaction = WalletTransaction::where('reference', $this->transactionReference)->first();

        if (!$transaction) {
            return;
        }

        if ($transaction->status !== 'pending') {
            return;
        }

        try {
            $response = $billsService->payAirtime(
                $this->phoneNumber,
                (float) $this->amount,
                $this->serviceCategoryId
            );

            if ($response['json']['statusCode'] === 200 && $response['json']['data']['status'] === 'successful') {
                $this->handleSuccessfulPurchase($transaction, $response);
            } else {
                $this->handleFailedPurchase($transaction, $response);
            }

        } catch (\Exception $e) {
            Log::error('ProcessAirtimePurchase - exception', [
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

    private function handleSuccessfulPurchase(WalletTransaction $transaction, array $response): void
    {
        DB::transaction(function () use ($transaction, $response) {
            $transaction->status = 'completed';
            $transaction->metadata = array_merge((array) $transaction->metadata, [
                'tx_ref' => $response['json']['data']['reference'] ?? null,
                'sf_ref' => $response['json']['data']['reference'] ?? null,
                'network' => SafehavenAirtimeProvider::where('_id', $response['json']['data']['serviceCategoryId'])
                        ->first()?->identifier ?? null,
                'serviceCategoryId' => $response['json']['data']['serviceCategoryId'] ?? null,
                'completed_at' => now()->toDateTimeString()
            ]);
            $transaction->save();
        });
    }

    private function handleFailedPurchase(WalletTransaction $transaction, array $response): void
    {
        DB::transaction(function () use ($transaction, $response) {
            $user = User::find($this->userId);
            $wallet = $user->wallet;

            // Refund the amount
            DB::table('wallets')
                ->where('id', $wallet->id)
                ->increment('balance', $transaction->amount);

            $transaction->status = 'failed';
            $transaction->metadata = array_merge((array) $transaction->metadata, [
                'failure_reason' => $response['json']['message'] ?? 'Payment failed',
                'failed_at' => now()->toDateTimeString()
            ]);
            $transaction->save();

        });
    }

    public function failed(\Throwable $exception): void
    {

        $transaction = WalletTransaction::where('reference', $this->transactionReference)->first();

        if ($transaction && $transaction->status === 'pending') {
            $this->handleFailedPurchase($transaction, [
                'json' => ['message' => 'Job failed after maximum retries: ' . $exception->getMessage()]
            ]);
        }
    }
}
