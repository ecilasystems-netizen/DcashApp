<?php

declare(strict_types=1);

namespace App\Services\SafeHavenApi;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SafehavenBillsPaymentService
{
    protected AuthentificationService $authService;
    protected string $baseApi;
    protected array $headers;
    protected string $debitAccountNumber;

    public function __construct(AuthentificationService $authService)
    {
        $this->authService = $authService;
        $this->baseApi = $authService->getBaseApi();
        $this->headers = $authService->getHeaders();
        $this->debitAccountNumber = config('safehaven.debit_account_number');

    }

    protected function sendPost(string $endpoint, array $payload, bool $retry = true): array
    {
        Log::info('SafehavenBillsPaymentService - request', [
            'endpoint' => $endpoint,
            'payload' => $payload,
            'retry' => $retry
        ]);

        try {
            $response = Http::withHeaders($this->headers)
                ->timeout(30)
                ->connectTimeout(15)
                ->post($this->baseApi.$endpoint, $payload);

            $status = $response->status();
            $body = $response->body();
            $json = null;

            try {
                $json = $response->json();
            } catch (\Throwable $e) {
                Log::warning('SafehavenBillsPaymentService - json decode failed', [
                    'body' => $body,
                    'error' => $e->getMessage()
                ]);
            }

            if (is_array($json) && isset($json['statusCode']) && is_numeric($json['statusCode'])) {
                $status = (int) $json['statusCode'];
            }

            Log::info('SafehavenBillsPaymentService - response', [
                'status' => $status,
                'body' => $body,
                'json' => $json
            ]);

            if ($status === 403 && $retry) {
                Log::warning('SafehavenBillsPaymentService - token expired, refreshing');

                try {
                    $this->authService->refreshToken();
                    $this->headers = $this->authService->getHeaders();
                    Log::info('SafehavenBillsPaymentService - retrying request with new token');

                    return $this->sendPost($endpoint, $payload, false);

                } catch (\Exception $e) {
                    Log::error('SafehavenBillsPaymentService - token refresh failed, cannot retry', [
                        'error' => $e->getMessage()
                    ]);

                    return [
                        'status' => 500,
                        'body' => $e->getMessage(),
                        'json' => ['error' => $e->getMessage()]
                    ];
                }
            }

            if ($status >= 400) {
                Log::error('SafehavenBillsPaymentService - request failed', [
                    'status' => $status,
                    'endpoint' => $endpoint,
                    'response_body' => $body,
                    'response_json' => $json,
                ]);
            }

            return [
                'status' => $status,
                'json' => $json,
            ];

        } catch (\Exception $e) {
            Log::error('SafehavenBillsPaymentService - request exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'status' => 500,
                'body' => $e->getMessage(),
                'json' => ['error' => $e->getMessage()],
            ];
        }
    }

    public function payAirtime(string $phoneNumber, float $amount, string $provider): array
    {
        $payload = [
            'phoneNumber' => $phoneNumber,
            'amount' => $amount,
            'serviceCategoryId' => $provider,
            'debitAccountNumber' => $this->debitAccountNumber,
            'channel' => 'WEB',
            ];

        Log::info('SafehavenBillsPaymentService - paying airtime', [
            'phoneNumber' => $phoneNumber,
            'serviceCategoryId' => $provider,
            'amount' => $amount
        ]);

        return $this->sendPost('/vas/pay/airtime', $payload);
    }

    public function buyDataBundle(
        string $phoneNumber,
        string $bundleCode,
        string $provider,
        float $amount = 0
    ): array {
        $payload = [
            'phoneNumber' => $phoneNumber,
            'bundleCode' => $bundleCode,
            'serviceCategoryId' => $provider,
            'debitAccountNumber' => $this->debitAccountNumber,
            'amount' => $amount,
            'channel' => 'WEB',
        ];

        Log::info('SafehavenBillsPaymentService - buying data bundle', [
            'phoneNumber' => $phoneNumber,
            'bundleCode' => $bundleCode,
            'serviceCategoryId' => $provider,
            'amount' => $amount
        ]);

        return $this->sendPost('/bills/data', $payload);
    }

    public function verifyBillProvider(
        string $billType,
        string $accountNumber,
        string $provider
    ): array {
        $payload = [
            'billType' => $billType,
            'accountNumber' => $accountNumber,
            'provider' => $provider,
        ];

        Log::info('SafehavenBillsPaymentService - verifying bill provider', [
            'billType' => $billType,
            'accountNumber' => $accountNumber,
            'provider' => $provider
        ]);

        return $this->sendPost('/bills/verify', $payload);
    }

    public function payUtilityBill(
        string $billType,
        string $accountNumber,
        float $amount,
        string $provider,
        ?string $reference = null
    ): array {
        $payload = [
            'billType' => $billType,
            'accountNumber' => $accountNumber,
            'amount' => $amount,
            'provider' => $provider,
            'debitAccountNumber' => $this->debitAccountNumber,
        ];

        if ($reference) {
            $payload['reference'] = $reference;
        }

        Log::info('SafehavenBillsPaymentService - paying utility bill', [
            'billType' => $billType,
            'accountNumber' => $accountNumber,
            'amount' => $amount,
            'provider' => $provider
        ]);

        return $this->sendPost('/bills/utility', $payload);
    }

    public function purchaseCableTvSubscription(
        string $accountNumber,
        string $plan,
        string $provider,
        ?string $reference = null
    ): array {
        $payload = [
            'accountNumber' => $accountNumber,
            'plan' => $plan,
            'provider' => $provider,
            'debitAccountNumber' => $this->debitAccountNumber,
        ];

        if ($reference) {
            $payload['reference'] = $reference;
        }

        Log::info('SafehavenBillsPaymentService - purchasing cable TV subscription', [
            'accountNumber' => $accountNumber,
            'plan' => $plan,
            'provider' => $provider
        ]);

        return $this->sendPost('/bills/tv', $payload);
    }
}
