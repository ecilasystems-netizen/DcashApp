<?php

declare(strict_types=1);

namespace App\Services\SafeHavenApi;

use App\Models\SafehavenDataBundle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SafehavenDataBundleService
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

    /**
     * Get supported network providers for data bundles using the correct serviceCategoryId
     */
    public function getSupportedProviders(): array
    {
        return [
            '6502eb6e65463b201bf8065f' => 'MTN',
            '61efad06da92348f9dde5fa1' => 'GLO',
            '61efad12da92348f9dde5fa4' => 'AIRTEL',
            '61efad1dda92348f9dde5fa7' => '9MOBILE'
        ];
    }

    /**
     * Get network logos mapped to serviceCategoryId
     */
    public function getNetworkLogos(): array
    {
        return [
            '6502eb6e65463b201bf8065f' => asset('https://res.cloudinary.com/sudo-africa/image/upload/v1646209736/SafeHavenVAS/New-mtn-logo_u1dy66.jpg'),
            '61efad06da92348f9dde5fa1' => asset('https://res.cloudinary.com/sudo-africa/image/upload/v1646208567/SafeHavenVAS/kindpng_4648442_1_ibibke.png'),
            '61efad12da92348f9dde5fa4' => asset('https://res.cloudinary.com/sudo-africa/image/upload/v1646208565/SafeHavenVAS/Airtel-Logo-Vector_1_hwmiic.png'),
            '61efad1dda92348f9dde5fa7' => asset('https://res.cloudinary.com/sudo-africa/image/upload/v1646208565/SafeHavenVAS/download_1_y1c4lr.png')
        ];
    }

    /**
     * Get data bundles for a specific provider categorized by duration
     */
    public function getBundlesForProvider(string $providerId): Collection
    {
        return SafehavenDataBundle::where('serviceCategoryId', $providerId)
            ->where('status', true)
            ->orderBy('amount', 'asc')
            ->get()
            ->groupBy(function ($bundle) {
                return $this->categorizeBundleByDuration($bundle->duration_days);
            });
    }

    /**
     * Categorize a bundle by duration days
     */
    protected function categorizeBundleByDuration(?int $durationDays): string
    {
        if (!$durationDays) {
            return 'others';
        }

        if ($durationDays <= 1) {
            return 'daily';
        }

        if ($durationDays <= 7) {
            return 'weekly';
        }

        if ($durationDays <= 31) {
            return 'monthly';
        }

        return 'others';
    }

    /**
     * Get all active data bundles categorized by duration
     */
    public function getAllBundlesCategorized(): Collection
    {
        return SafehavenDataBundle::where('status', true)
            ->orderBy('amount', 'asc')
            ->get()
            ->groupBy(function ($bundle) {
                return $this->categorizeBundleByDuration($bundle->duration_days);
            });
    }

    /**
     * Get bundle by code
     */
    public function getBundleByCode(string $bundleCode): ?SafehavenDataBundle
    {
        return SafehavenDataBundle::where('bundle_code', $bundleCode)
            ->where('status', true)
            ->first();
    }

    /**
     * Purchase data bundle
     */
    public function purchaseDataBundle(
        string $phoneNumber,
        int|string $bundleCode,
        string $serviceCategoryId,
        float $amount
    ): array {

        $bundleCode = (string) $bundleCode;

        $payload = [
            'phoneNumber' => $phoneNumber,
            'bundleCode' => $bundleCode,
            'serviceCategoryId' => $serviceCategoryId,
            'debitAccountNumber' => $this->debitAccountNumber,
            'amount' => $amount,
            'channel' => 'WEB',
        ];

        return $this->sendPost('/vas/pay/data', $payload);
    }

    protected function sendPost(string $endpoint, array $payload, bool $retry = true): array
    {

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

    /**
     * Format data size for display and fix undefined constant error
     */
    public function formatDataSize(string $dataSize): string
    {
        if (strpos(strtolower($dataSize), 'mb') !== false) {
            // Use FILTER_SANITIZE_NUMBER_INT to extract numeric values
            $size = (float) filter_var($dataSize, FILTER_SANITIZE_NUMBER_INT);
            if ($size >= 1024) {
                return number_format($size / 1024, 1).'GB';
            }
        }

        return $dataSize;
    }

    /**
     * Format validity period for display
     */
    public function formatValidityPeriod(string $validity, int $durationDays): string
    {
        if ($durationDays === 1) {
            return '24 hours';
        }

        if ($durationDays === 7) {
            return '7 days';
        }

        if ($durationDays === 30 || $durationDays === 31) {
            return '30 days';
        }

        return "{$durationDays} days";
    }
}
