<?php

namespace App\Services\SafeHavenApi;

use App\Models\SafehavenBank;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BanksService
{
    protected AuthentificationService $authService;
    protected string $baseApi;
    protected array $headers;

    public function __construct(AuthentificationService $authService)
    {
        $this->authService = $authService;
        $this->baseApi = $authService->getBaseApi();
        $this->headers = $authService->getHeaders();
    }

    public function getBankList(): array
    {
        $endpoint = '/transfers/banks';

        Log::info('BanksService - fetching bank list');

        try {
            $response = Http::withHeaders($this->headers)
                ->get($this->baseApi.$endpoint);

            $status = $response->status();
            $body = $response->body();
            $json = null;

            try {
                $json = $response->json();
            } catch (\Throwable $e) {
                Log::warning('BanksService - json decode failed', [
                    'body' => $body,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('BanksService - bank list response', [
                'status' => $status,
                'bank_count' => isset($json['data']) ? count($json['data']) : 0
            ]);

            if ($status === 403) {
                Log::warning('BanksService - token expired, refreshing');

                try {
                    $this->authService->refreshToken();
                    $this->headers = $this->authService->getHeaders();
                    Log::info('BanksService - retrying request with new token');

                    return $this->getBankList();

                } catch (\Exception $e) {
                    Log::error('BanksService - token refresh failed', [
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
                Log::error('BanksService - get bank list failed', [
                    'status' => $status,
                    'error_message' => $json['message'] ?? $body
                ]);
            }

            return [
                'status' => $status,
                'json' => $json,
            ];

        } catch (\Exception $e) {
            Log::error('BanksService - get bank list exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'status' => 500,
                'body' => $e->getMessage(),
                'json' => ['error' => $e->getMessage()],
            ];
        }
    }

    public function getBankByRoutingKey(string $routingKey): ?array
    {
        $response = $this->getBankList();

        if ($response['status'] === 200 && isset($response['json']['data'])) {
            foreach ($response['json']['data'] as $bank) {
                if ($bank['routingKey'] === $routingKey || $bank['bankCode'] === $routingKey) {
                    return $bank;
                }
            }
        }

        return null;
    }

    public function getBankByName(string $name): ?array
    {
        $response = $this->getBankList();

        if ($response['status'] === 200 && isset($response['json']['data'])) {
            $searchName = strtolower($name);
            foreach ($response['json']['data'] as $bank) {
                if (str_contains(strtolower($bank['name']), $searchName)) {
                    return $bank;
                }
            }
        }

        return null;
    }

    public function getBanksFromDatabase()
    {
        return SafehavenBank::orderBy('name')->get();
    }

    public function getBankFromDatabaseByRoutingKey(string $routingKey): ?SafehavenBank
    {
        return SafehavenBank::where('code', $routingKey)->first();
    }
}
