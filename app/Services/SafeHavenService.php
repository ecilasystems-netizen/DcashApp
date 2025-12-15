<?php

namespace App\Services;

use App\Models\SafehavenBank;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SafeHavenService
{
    protected string $clientId;
    protected string $bearerToken;
    protected string $baseApi;
    protected array $headers;
    protected string $client_assertion_type;
    protected string $client_assertion;


    public function __construct()
    {
        $this->clientId = '68de621ccd23d700243524ba';
        $this->bearerToken = env('SERVICES_SAFEHAVEN_TOKEN');
        $this->baseApi = 'https://api.safehavenmfb.com';
        $this->client_assertion_type = 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer';
        $this->client_assertion = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2RjYXNod2FsbGV0LmNvbSIsInN1YiI6ImFiZTgxZDE1NTgxM2VlOTBlMDRjODQ4YzJkMWJjMmE3IiwiYXVkIjoiaHR0cHM6Ly9hcGkuc2FmZWhhdmVubWZiLmNvbSIsImlhdCI6MTc2MjQ1MDk0MywiZXhwIjozODEyMTcwODI1fQ.UxtDYvloqTXUfSfWkudLVT2SDG95PRppwcLK4PJjou7P2YvFmOAyQGx4DCsrD4LcqvJbBEbf5lFkj0Dz8UHI866PeOJMayxP5SEdhVpqqP1coXVBf2H5hofiWYTr_ywBWsed4T2CCWvaq6evqtC1GJpAJ0HK8tiAXGkyLh8G1RM';

        $this->headers = [
            'ClientID' => $this->clientId,
            'Authorization' => "Bearer {$this->bearerToken}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function sendPost(string $endpoint, array $payload, bool $retry = true): array
    {
        Log::info('SafeHavenService - request', [
            'endpoint' => $endpoint,
            'payload' => $payload,
            'retry' => $retry
        ]);

        try {
            $response = Http::withHeaders($this->headers)
                ->timeout(30)
                ->connectTimeout(15)
                ->post($this->baseApi.$endpoint, $payload);

            // original HTTP status and body
            $status = $response->status();
            $body = $response->body();
            $json = null;

            try {
                $json = $response->json();
            } catch (\Throwable $e) {
                Log::warning('SafeHavenService - json decode failed', [
                    'body' => $body,
                    'error' => $e->getMessage()
                ]);
            }

            // Prefer API's embedded statusCode when present (normalizes inconsistent responses)
            if (is_array($json) && isset($json['statusCode']) && is_numeric($json['statusCode'])) {
                $status = (int) $json['statusCode'];
            }

            Log::info('SafeHavenService - response', [
                'status' => $status,
                'body' => $body,
                'json' => $json
            ]);

            // Handle token expiration (403 Forbidden)
            if ($status === 403 && $retry) {
                Log::warning('SafeHavenService - token expired, refreshing');

                try {
                    $newToken = $this->refreshToken();
                    Log::info('SafeHavenService - retrying request with new token');

                    // Retry with new token (set retry to false to prevent infinite loop)
                    return $this->sendPost($endpoint, $payload, false);

                } catch (\Exception $e) {
                    Log::error('SafeHavenService - token refresh failed, cannot retry', [
                        'error' => $e->getMessage()
                    ]);

                    return [
                        'status' => 500,
                        'body' => $e->getMessage(),
                        'json' => ['error' => $e->getMessage()]
                    ];
                }
            }

            // Handle other error statuses
            if ($status >= 400) {
                Log::error('SafeHavenService - request failed', [
                    'status' => $status,
                    'endpoint' => $endpoint,
                    'payload' => $payload,
                    'response_body' => $body,
                    'response_json' => $json,
                    'error_message' => is_array($json) ? ($json['message'] ?? null) : null,
                    'error_code' => is_array($json) ? ($json['errorCode'] ?? $json['error_code'] ?? null) : null,
                    'status_code' => is_array($json) ? ($json['statusCode'] ?? null) : null,
                    'errors' => is_array($json) ? ($json['errors'] ?? $json['data'] ?? null) : null,
                    'headers' => array_keys($this->headers),
                ]);
            }

            return [
                'status' => $status,
                'body' => $body,
                'json' => $json,
            ];

        } catch (\Exception $e) {
            Log::error('SafeHavenService - request exception', [
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
            'debitAccountNumber' => '0119358126',
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


        Log::info('SafeHavenService - creating subaccount', [
            'payload' => array_merge($payload, ['identityNumber' => '***masked***'])
        ]);

        return $this->sendPost('/accounts/v2/subaccount', $payload);
    }

    public function refreshToken(): string
    {
        Log::info('SafeHavenService - refreshing token');

        try {
            $payload = [
                'client_id' => 'abe81d155813ee90e04c848c2d1bc2a7',
                'grant_type' => 'client_credentials',
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => $this->client_assertion,
            ];

            Log::info('SafeHavenService - token request', ['payload' => $payload]);

            $response = Http::post($this->baseApi.'/oauth2/token', $payload);

            $status = $response->status();
            $json = $response->json();

            Log::info('SafeHavenService - token response', [
                'status' => $status,
                'has_access_token' => isset($json['access_token']),
                'response' => $json
            ]);

            // Success: status 200 and has access_token
            if ($status === 201 && isset($json['access_token'])) {
                $this->bearerToken = $json['access_token'];
                $this->headers['Authorization'] = "Bearer {$this->bearerToken}";

                Log::info('SafeHavenService - token refreshed successfully');
                return $this->bearerToken;
            }

            // If we got here, refresh failed
            $errorMsg = $json['message'] ?? json_encode($json);
            Log::error('SafeHavenService - token refresh failed', [
                'status' => $status,
                'error' => $errorMsg
            ]);

            throw new \Exception("Token refresh failed with status {$status}");

        } catch (\Exception $e) {
            Log::error('SafeHavenService - token refresh exception', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
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

        Log::info('SafeHavenService - fetching accounts', [
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
                Log::warning('SafeHavenService - json decode failed', [
                    'body' => $body,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('SafeHavenService - accounts response', [
                'status' => $status,
                'account_count' => $json['data'] ?? 0,
                'pagination' => $json['pagination'] ?? null
            ]);

            // Handle token expiration (403 Forbidden)
            if ($status === 403) {
                Log::warning('SafeHavenService - token expired, refreshing');

                try {
                    $this->refreshToken();
                    Log::info('SafeHavenService - retrying request with new token');

                    // Retry with new token
                    return $this->getAccounts($accountNumber, $page, $limit);

                } catch (\Exception $e) {
                    Log::error('SafeHavenService - token refresh failed', [
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
                Log::error('SafeHavenService - get accounts failed', [
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
            Log::error('SafeHavenService - get accounts exception', [
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


    public function getBankList(): array
    {
        $endpoint = '/transfers/banks';

        Log::info('SafeHavenService - fetching bank list');

        try {
            $response = Http::withHeaders($this->headers)
                ->get($this->baseApi.$endpoint);

            $status = $response->status();
            $body = $response->body();
            $json = null;

            try {
                $json = $response->json();
            } catch (\Throwable $e) {
                Log::warning('SafeHavenService - json decode failed', [
                    'body' => $body,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('SafeHavenService - bank list response', [
                'status' => $status,
                'bank_count' => isset($json['data']) ? count($json['data']) : 0
            ]);

            // Handle token expiration (403 Forbidden)
            if ($status === 403) {
                Log::warning('SafeHavenService - token expired, refreshing');

                try {
                    $this->refreshToken();
                    Log::info('SafeHavenService - retrying request with new token');

                    // Retry with new token
                    return $this->getBankList();

                } catch (\Exception $e) {
                    Log::error('SafeHavenService - token refresh failed', [
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
                Log::error('SafeHavenService - get bank list failed', [
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
            Log::error('SafeHavenService - get bank list exception', [
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


    /**
     * bankslist
     **/

    public function syncBanksToDatabase(): array
    {
        Log::info('SafeHavenService - syncing banks to database');

        try {
            $response = $this->getBankList();

            if ($response['status'] !== 200 || !isset($response['json']['data'])) {
                Log::error('SafeHavenService - failed to fetch banks for sync', [
                    'status' => $response['status']
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to fetch banks from SafeHaven',
                    'synced' => 0
                ];
            }

            $banks = $response['json']['data'];
            $syncedCount = 0;

            foreach ($banks as $bankData) {
                SafehavenBank::updateOrCreate(
                    ['code' => $bankData['routingKey']],
                    [
                        'name' => $bankData['name'],
                    ]
                );

                $syncedCount++;
            }

            Log::info('SafeHavenService - banks synced successfully', [
                'count' => $syncedCount
            ]);

            return [
                'success' => true,
                'message' => 'Banks synced successfully',
                'synced' => $syncedCount
            ];

        } catch (\Exception $e) {
            Log::error('SafeHavenService - bank sync exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while syncing banks',
                'error' => $e->getMessage(),
                'synced' => 0
            ];
        }
    }


    public function getBanksFromDatabase()
    {
        return SafehavenBank::orderBy('name')->get();
    }


    public function getBankFromDatabaseByRoutingKey(string $routingKey): ?SafehavenBank
    {
        return SafehavenBank::where('code', $routingKey)
            ->first();
    }


    public function accountNameEnquiry(
        string $accountNumber,
        string $bankCode,
        ?string $debitAccountNumber = null
    ): array {
        $payload = [
            'accountNumber' => $accountNumber,
            'bankCode' => $bankCode,
        ];

        if ($debitAccountNumber) {
            $payload['debitAccountNumber'] = $debitAccountNumber;
        }

        Log::info('SafeHavenService - name enquiry', [
            'accountNumber' => $accountNumber,
            'bankCode' => $bankCode
        ]);

        $response = $this->sendPost('/transfers/name-enquiry', $payload);

        if (in_array($response['status'], [200, 201]) && isset($response['json']['data'])) {
            Log::info('SafeHavenService - name enquiry successful', [
                'accountName' => $response['json']['data']['accountName'] ?? null,
                'kycLevel' => $response['json']['data']['kycLevel'] ?? null
            ]);
        } else {
            Log::error('SafeHavenService - name enquiry failed', [
                'status' => $response['status'],
                'message' => $response['json']['message'] ?? 'Unknown error'
            ]);
        }

        return $response;
    }


    public function initiateTransfer(array $data): array
    {
        $payload = [
            'nameEnquiryReference' => $data['nameEnquiryReference'],
            'debitAccountNumber' => $data['debitAccountNumber'],
            'amount' => $data['amount'],
            'narration' => $data['narration'] ?? '',
            'paymentReference' => $data['paymentReference'] ?? null,
            'clientReference' => $data['clientReference'] ?? null,
        ];

        // Optional fields
        if (isset($data['saveBeneficiary'])) {
            $payload['saveBeneficiary'] = $data['saveBeneficiary'];
        }

        if (isset($data['beneficiaryName'])) {
            $payload['beneficiaryName'] = $data['beneficiaryName'];
        }

        Log::info('SafeHavenService - initiating transfer', [
            'debitAccount' => $data['debitAccountNumber'],
            'amount' => $data['amount'],
            'nameEnquiryRef' => $data['nameEnquiryReference']
        ]);

        $response = $this->sendPost('/transfers', $payload);

        if (in_array($response['status'], [200, 201]) && isset($response['json']['data'])) {
            Log::info('SafeHavenService - transfer successful', [
                'sessionId' => $response['json']['data']['sessionId'] ?? null,
                'status' => $response['json']['data']['status'] ?? null,
                'amount' => $response['json']['data']['amount'] ?? null
            ]);
        } else {
            Log::error('SafeHavenService - transfer failed', [
                'status' => $response['status'],
                'message' => $response['json']['message'] ?? 'Unknown error'
            ]);
        }

        return $response;
    }


    public function getTransferStatus(string $sessionId): array
    {
        $endpoint = "/transfers/{$sessionId}";

        Log::info('SafeHavenService - getting transfer status', [
            'sessionId' => $sessionId
        ]);

        try {
            $response = Http::withHeaders($this->headers)
                ->get($this->baseApi.$endpoint);

            $status = $response->status();
            $json = $response->json();

            Log::info('SafeHavenService - transfer status response', [
                'status' => $status,
                'transferStatus' => $json['data']['status'] ?? null
            ]);

            return [
                'status' => $status,
                'body' => $response->body(),
                'json' => $json,
            ];

        } catch (\Exception $e) {
            Log::error('SafeHavenService - get transfer status exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'status' => 500,
                'body' => $e->getMessage(),
                'json' => ['error' => $e->getMessage()],
            ];
        }
    }

}
