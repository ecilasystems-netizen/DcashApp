<?php

declare(strict_types=1);

namespace App\Jobs\SafeHaven;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\SafeHavenApi\SafehavenElectricityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SafeHavenProcessElectricityPurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;
    public int $backoff = 5;

    public function __construct(
        public string $transactionReference,
        public int $userId,
        public string $meterNumber,
        public string $provider,
        public string $meterType,
        public int $amount,
        public ?string $customerName
    ) {
    }

    public function handle(SafehavenElectricityService $electricityService): void
    {
        $transaction = WalletTransaction::where('reference', $this->transactionReference)->first();

        if (!$transaction) {
            return;
        }

        if ($transaction->status !== 'pending') {

            return;
        }

        try {
            $response = $electricityService->purchaseElectricity(
                $this->meterNumber,
                (float) $this->amount,
                $this->provider,
                $this->meterType,
            );

            if (in_array($response['status'], [200, 201])) {
                $this->handleSuccessfulPurchase($transaction, $response);
            } else {
                $this->handleFailedPurchase($transaction, $response);
            }

        } catch (\Exception $e) {


            $this->handleFailedPurchase($transaction, [
                'json' => ['message' => $e->getMessage()]
            ]);

            throw $e;
        }
    }

    private function handleSuccessfulPurchase(WalletTransaction $transaction, array $response): void
    {
        DB::transaction(function () use ($transaction, $response) {
            $responseData = $response['json']['data'] ?? [];

            $transaction->status = 'completed';
            $transaction->metadata = array_merge((array) $transaction->metadata, [
                'tx_ref' => $responseData['reference'] ?? null,
                'sf_ref' => $responseData['reference'] ?? null,
                'token' => $responseData['utilityToken'] ?? null,
                'units' => $responseData['tokenValue'] ?? null,
                'tariff' => $responseData['tariff'] ?? null,
                'api_response' => $response['json'] ?? null,
                'trx_id' => $responseData['id'] ?? null,
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
                ->increment('balance', $this->amount);

            $transaction->status = 'failed';
            $transaction->metadata = array_merge((array) $transaction->metadata, [
                'failure_reason' => $response['json']['message'] ?? 'Purchase failed',
                'api_response' => $response['json'] ?? null,
                'failed_at' => now()->toDateTimeString(),
                'refunded' => true
            ]);
            $transaction->save();

        });
    }

    public function failed(\Throwable $exception): void
    {


        $transaction = WalletTransaction::where('reference', $this->transactionReference)->first();

        if ($transaction && $transaction->status === 'pending') {
            $this->handleFailedPurchase($transaction, [
                'json' => ['message' => 'Job failed after maximum retries: '.$exception->getMessage()]
            ]);
        }
    }
}
