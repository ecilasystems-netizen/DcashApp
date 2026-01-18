<?php

    declare(strict_types=1);

    namespace App\Services\SafeHavenApi;

    use App\Models\SafehavenTvBundle;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Collection;

    class SafehavenTvBillsService
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
            try {
                $response = Http::withHeaders($this->headers)
                    ->timeout(30)
                    ->connectTimeout(15)
                    ->post($this->baseApi . $endpoint, $payload);

                $status = $response->status();
                $body = $response->body();
                $json = null;

                try {
                    $json = $response->json();
                } catch (\Throwable $e) {
                    Log::warning('SafehavenTvBillsService - json decode failed', [
                        'body' => $body,
                        'error' => $e->getMessage()
                    ]);
                }

                // Log full response for debugging
                Log::info('SafehavenTvBillsService - raw response', [
                    'status' => $status,
                    'headers' => $response->headers(),
                    'body' => $body
                ]);

                if (is_array($json) && isset($json['statusCode']) && is_numeric($json['statusCode'])) {
                    $status = (int) $json['statusCode'];
                }

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

                //long the json response
                Log::info('SafehavenTvBillsService - response', [
                    'status' => $status,
                    'body' => $body,
                    'json' => $json
                ]);

                return [
                    'status' => $status,
                    'json' => $json,
                ];

            } catch (\Exception $e) {
                Log::error('SafehavenTvBillsService - request exception', [
                    'endpoint' => $endpoint,
                    'error' => $e->getMessage()
                ]);

                return [
                    'status' => 500,
                    'body' => $e->getMessage(),
                    'json' => ['error' => $e->getMessage()],
                ];
            }
        }

        public function verifyUser(string $cardNumber, string $provider): array
        {
            $validProviders = array_keys($this->getSupportedProviders());
            if (!in_array($provider, $validProviders, true)) {
                return [
                    'status' => 400,
                    'json' => [
                        'statusCode' => 400,
                        'message' => 'Invalid provider code. Please select a valid TV provider.'
                    ]
                ];
            }

            $payload = [
                'entityNumber' => $cardNumber,
                'serviceCategoryId' => $provider,
            ];

            Log::info('SafehavenTvBillsService - verifying user', [
                'entityNumber' => $cardNumber,
                'serviceCategoryId' => $provider,
            ]);

            return $this->sendPost('/vas/verify', $payload);
        }

        public function purchaseTvBill(
            string $cardNumber,
            string $bundleCode,
            string $serviceCategoryId,
            float $amount
        ): array {
            $payload = [
                'cardNumber' => $cardNumber,
                'bundleCode' => $bundleCode,
                'amount' => $amount,
                'serviceCategoryId' => $serviceCategoryId,
                'debitAccountNumber' => $this->debitAccountNumber,
                'channel' => 'WEB',
            ];

            Log::info('SafehavenTvBillsService - purchasing TV bill', [
                'cardNumber' => $cardNumber,
                'bundleCode' => $bundleCode,
                'serviceCategoryId' => $serviceCategoryId,
                'amount' => $amount
            ]);

            return $this->sendPost('/vas/pay1/cable-tv', $payload);
        }

        /**
         * Get supported TV providers with their SafeHaven IDs
         */
        public function getSupportedProviders(): array
        {
            return [
                '61efad38da92348f9dde5faa' => 'DStv',
                '61efad45da92348f9dde5fad' => 'GOtv',
                '61efad50da92348f9dde5fb0' => 'StarTimes',
            ];
        }

        /**
         * Get all active TV bundles grouped by provider
         */
        public function getBundlesGroupedByProvider(): Collection
        {
            return SafehavenTvBundle::active()
                ->orderBy('serviceCategoryId')
                ->orderBy('amount')
                ->get()
                ->groupBy('serviceCategoryId')
                ->map(function ($bundles, $providerId) {
                    return [
                        'provider' => $this->getSupportedProviders()[$providerId] ?? 'Unknown',
                        'providerId' => $providerId,
                        'bundles' => $bundles->map(fn($bundle) => [
                            'id' => $bundle->id,
                            'name' => $bundle->name,
                            'bundleCode' => $bundle->bundleCode,
                            'amount' => (float) $bundle->amount,
                            'formattedAmount' => $bundle->formatted_amount,
                            'duration' => $bundle->duration,
                            'formattedDuration' => $bundle->formatted_duration,
                            'isAmountFixed' => $bundle->isAmountFixed,
                        ])
                    ];
                });
        }

        /**
         * Get bundles for a specific provider
         */
        public function getBundlesForProvider(string $providerId): Collection
        {
            return SafehavenTvBundle::active()
                ->forProvider($providerId)
                ->orderBy('amount')
                ->get()
                ->map(fn($bundle) => [
                    'id' => $bundle->id,
                    'name' => $bundle->name,
                    'bundleCode' => $bundle->bundleCode,
                    'amount' => (float) $bundle->amount,
                    'formattedAmount' => $bundle->formatted_amount,
                    'duration' => $bundle->duration,
                    'formattedDuration' => $bundle->formatted_duration,
                    'isAmountFixed' => $bundle->isAmountFixed,
                ]);
        }

        /**
         * Get a specific bundle by code
         */
        public function getBundleByCode(string $bundleCode): ?SafehavenTvBundle
        {
            return SafehavenTvBundle::active()
                ->where('bundleCode', $bundleCode)
                ->first();
        }
    }
