<?php

namespace App\Services\SafeHavenApi;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AccountsService
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
        $this->debitAccountNumber = env('SAFEHAVEN_API_DEBIT_ACCOUNT_NUMBER', '0119358126');
    }

    protected function sendPost(string $endpoint, array $payload, bool $retry = true): array
    {
        Log::info('AccountsService - request', [
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
                Log::warning('AccountsService - json decode failed', [
                    'body' => $body,
                    'error' => $e->getMessage()
                ]);
            }

            if (is_array($json) && isset($json['statusCode']) && is_numeric($json['statusCode'])) {
                $status = (int) $json['statusCode'];
            }

            Log::info('AccountsService - response', [
                'status' => $status,
                'body' => $body,
                'json' => $json
            ]);

            if ($status === 403 && $retry) {
                Log::warning('AccountsService - token expired, refreshing');

                try {
                    $this->authService->refreshToken();
                    $this->headers = $this->authService->getHeaders();
                    Log::info('AccountsService - retrying request with new token');

                    return $this->sendPost($endpoint, $payload, false);

                } catch (\Exception $e) {
                    Log::error('AccountsService - token refresh failed, cannot retry', [
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
                Log::error('AccountsService - request failed', [
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
            Log::error('AccountsService - request exception', [
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

    public function initiateBvnVerification(string $bvn): array
    {
        $payload = [
            'type' => 'BVN',
            'async' => false,
            'debitAccountNumber' => $this->debitAccountNumber,
            'number' => $bvn,
        ];

        return $this->sendPost('/identity/v2', $payload);
    }

    public function validateBvnOtp(string $reference, string $otp): array
    {
        $payload = [
            'type' => 'BVN',
            'identityId' => $reference,
            'otp' => $otp,
        ];

        return $this->sendPost('/identity/v2/validate', $payload);
    }

    public function createSafeHavenSubAccount(array $data): array
    {
        $payload = [
            'emailAddress' => $data['email'] ?? null,
            'phoneNumber' => $data['phone'] ?? null,
            'externalReference' => $data['externalReference'] ?? uniqid('ref_'),
            'identityType' => 'BVN',
            'autoSweep' => false,
            'identityId' => $data['identityId'] ?? null,
            'identityNumber' => $data['identityNumber'] ?? null,
        ];

        Log::info('AccountsService - creating subaccount', [
            'payload' => array_merge($payload, ['identityNumber' => '***masked***'])
        ]);

        return $this->sendPost('/accounts/v2/subaccount', $payload);
    }

    public function getAccounts(?string $accountNumber = null, int $page = 0, int $limit = 25): array
    {
        $queryParams = [
            'page' => $page,
            'limit' => $limit,
        ];

        if ($accountNumber) {
            $queryParams['accountNumber'] = $accountNumber;
        }

        $endpoint = '/accounts?'.http_build_query($queryParams);

        Log::info('AccountsService - fetching accounts', [
            'accountNumber' => $accountNumber,
            'page' => $page,
            'limit' => $limit
        ]);

        try {
            $response = Http::withHeaders($this->headers)
                ->get($this->baseApi.$endpoint);

            $status = $response->status();
            $body = $response->body();
            $json = null;

            try {
                $json = $response->json();
            } catch (\Throwable $e) {
                Log::warning('AccountsService - json decode failed', [
                    'body' => $body,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('AccountsService - accounts response', [
                'status' => $status,
                'account_count' => $json['data'] ?? 0,
                'pagination' => $json['pagination'] ?? null
            ]);

            if ($status === 403) {
                Log::warning('AccountsService - token expired, refreshing');

                try {
                    $this->authService->refreshToken();
                    $this->headers = $this->authService->getHeaders();
                    Log::info('AccountsService - retrying request with new token');

                    return $this->getAccounts($accountNumber, $page, $limit);

                } catch (\Exception $e) {
                    Log::error('AccountsService - token refresh failed', [
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
                Log::error('AccountsService - get accounts failed', [
                    'status' => $status,
                    'error_message' => $json['message'] ?? $body
                ]);
            }

            return [
                'status' => $status,
                'body' => $body,
                'json' => $json,
            ];

        } catch (\Exception $e) {
            Log::error('AccountsService - get accounts exception', [
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

    public function getAccountByNumber(string $accountNumber): ?array
    {
        $response = $this->getAccounts($accountNumber, 0, 1);

        if ($response['status'] === 200 && isset($response['json']['data'][0])) {
            return $response['json']['data'][0];
        }

        return null;
    }

    public function getAccountBalance(string $accountNumber): ?float
    {
        $account = $this->getAccountByNumber($accountNumber);

        return $account['accountBalance'] ?? null;
    }

}
