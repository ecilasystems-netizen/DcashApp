<?php

declare(strict_types=1);

namespace App\Services\SafeHavenApi;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SafehavenElectricityService
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


    public function verifyMeter(
        string $meterNumber,
        string $provider,
    ): array {
        $validProviders = array_keys($this->getSupportedProviders());
        if (!in_array($provider, $validProviders, true)) {

            return [
                'status' => 400,
                'json' => [
                    'statusCode' => 400,
                    'message' => 'Invalid provider code. Please select a valid electricity provider.'
                ]
            ];
        }

        // Try including vendType if SafeHaven requires it for verification
        $payload = [
            'entityNumber' => $meterNumber,
            'serviceCategoryId' => $provider,
        ];


        return $this->sendPost('/vas/verify', $payload);
    }


    public function getSupportedProviders(): array
    {
        return [
            'IKEDC' => 'Ikeja Electric',
            'EKEDC' => 'Eko Electric',
            'KEDCO' => 'Kano Electric',
            'PHED' => 'Port Harcourt Electric',
            'JED' => 'Jos Electric',
            'IBEDC' => 'Ibadan Electric',
            'KAEDCO' => 'Kaduna Electric',
            '61efac35da92348f9dde5f77' => 'Abuja Electric',
            'BEDC' => 'Benin Electric',
            'EEDC' => 'Enugu Electric',
        ];
    }


    protected function sendPost(string $endpoint, array $payload, bool $retry = true): array
    {
        Log::info('SafehavenElectricityService - request', [
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

            }

            // Extract status from response body if available
            if (is_array($json) && isset($json['statusCode']) && is_numeric($json['statusCode'])) {
                $status = (int) $json['statusCode'];
            }


            // Handle token expiry with automatic refresh and retry
            if ($status === 403 && $retry) {

                try {
                    $this->authService->refreshToken();
                    $this->headers = $this->authService->getHeaders();

                    return $this->sendPost($endpoint, $payload, false);

                } catch (\Exception $e) {

                    return [
                        'status' => 500,
                        'body' => $e->getMessage(),
                        'json' => ['error' => $e->getMessage()]
                    ];
                }
            }

            //log the response
            Log::info('SafehavenElectricityService - response', [
                'endpoint' => $endpoint,
                'status' => $status,
                'body' => $body,
                'json' => $json,
            ]);

            return [
                'status' => $status,
                'json' => $json,
            ];

        } catch (\Exception $e) {


            return [
                'status' => 500,
                'body' => $e->getMessage(),
                'json' => ['error' => $e->getMessage()],
            ];
        }
    }


    public function purchaseElectricity(
        string $meterNumber,
        float $amount,
        string $provider,
        string $meterType,
    ): array {
        $payload = [
            'meterNumber' => $meterNumber,
            'amount' => $amount,
            'serviceCategoryId' => $provider,
            'vendType' => strtoupper($meterType),
            'debitAccountNumber' => $this->debitAccountNumber,
            'channel' => 'WEB',
        ];

        return $this->sendPost('/vas/pay/utility', $payload);
    }


    public function isValidMeterType(string $meterType): bool
    {
        return in_array(strtoupper($meterType), ['PREPAID', 'POSTPAID'], true);
    }
}
