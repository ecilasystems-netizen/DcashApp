<?php

namespace App\Services;

use App\Models\SafehavenBank;
use Illuminate\Support\Facades\DB;
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
        $this->client_assertion_type = 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer';
        $this->clientId = config('safehaven.client_id');
        $this->bearerToken = config('safehaven.bearer_token');
        $this->baseApi = config('safehaven.base_url');
        $this->client_assertion = config('safehaven.client_assertion');

        $this->headers = [
            'ClientID' => $this->clientId,
            'Authorization' => "Bearer {$this->bearerToken}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
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

    public function getAccountBalance(string $accountNumber): ?float
    {
        $account = $this->getAccountByNumber($accountNumber);

        return $account['accountBalance'] ?? null;
    }

    public function getAccountByNumber(string $accountNumber): ?array
    {
        $response = $this->getAccounts($accountNumber, 0, 1);

        if ($response['status'] === 200 && isset($response['json']['data'][0])) {
            return $response['json']['data'][0];
        }

        return null;
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

    //test data
    public function importSafehavenDataBundles()
    {
        $bundles = [
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '2.5GB@N900 2-Day', 'bundle_code' => '20', 'amount' => 90000, 'data_size' => '2.5GB',
                'duration_days' => 2, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '3.2GB@N1000 2-Day', 'bundle_code' => '39', 'amount' => 100000, 'data_size' => '3.2GB',
                'duration_days' => 2, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => 'Get 6.75GB for N3000', 'bundle_code' => '34', 'amount' => 300000,
                'data_size' => '6.75GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '1GB+1.5Mins Talk@N500 1-day', 'bundle_code' => '18', 'amount' => 50000,
                'data_size' => '1GB', 'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '600MB+2mins+2sms@N500 7days', 'bundle_code' => '25', 'amount' => 50000,
                'data_size' => '600MB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '1GB@N800 7days', 'bundle_code' => '19', 'amount' => 80000, 'data_size' => '1GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '500MB@N500 7days', 'bundle_code' => '36', 'amount' => 50000, 'data_size' => '500MB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '6GB@N2,500 7days', 'bundle_code' => '9', 'amount' => 250000, 'data_size' => '6GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => 'N2500Talk+100MB@500 7days', 'bundle_code' => '24', 'amount' => 50000,
                'data_size' => '100MB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '1.8GB+6 mins@N1500 7days', 'bundle_code' => '25', 'amount' => 150000,
                'data_size' => '1.8GB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '11GB Weekly@N3500 7days', 'bundle_code' => '38', 'amount' => 350000,
                'data_size' => '11GB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '32GB+10000 talktime@N11000 30days', 'bundle_code' => '25', 'amount' => 1100000,
                'data_size' => '32GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '2.7GB+2mins@N2000 30days', 'bundle_code' => '9', 'amount' => 200000,
                'data_size' => '2.7GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '165GB Monthly@N35,000 30days', 'bundle_code' => '36', 'amount' => 3500000,
                'data_size' => '165GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '10GB+10mins@N4,500 30days', 'bundle_code' => '9', 'amount' => 450000,
                'data_size' => '10GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '2GB+2mins@N1500 30days', 'bundle_code' => '39', 'amount' => 150000, 'data_size' => '2GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '7GB@N3,500 30days', 'bundle_code' => '9', 'amount' => 350000, 'data_size' => '7GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '16GB+10mins@N6,500 30days', 'bundle_code' => '9', 'amount' => 650000,
                'data_size' => '16GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '20GB@N7,500 30days', 'bundle_code' => '9', 'amount' => 750000, 'data_size' => '20GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '36GB@N11,000 30days', 'bundle_code' => '9', 'amount' => 1100000, 'data_size' => '36GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '3.5GB+5mins@N2,500 30days', 'bundle_code' => '36', 'amount' => 250000,
                'data_size' => '3.5GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '5.5GB+15mins@N3,000 30days', 'bundle_code' => '25', 'amount' => 300000,
                'data_size' => '5.5GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '14.5GB Monthly Plan', 'bundle_code' => '9', 'amount' => 500000, 'data_size' => '14.5GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '12.5GB+36mins+15 SMS@N5000 30days', 'bundle_code' => '25', 'amount' => 550000,
                'data_size' => '12.5GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '25GB@N9,000 30days', 'bundle_code' => '36', 'amount' => 900000, 'data_size' => '25GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '75GB@N18000 30days', 'bundle_code' => '9', 'amount' => 1800000, 'data_size' => '75GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '12.5GB@N5,500 30days', 'bundle_code' => '9', 'amount' => 550000, 'data_size' => '12.5GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '7GB 2-Days Plan@1,800', 'bundle_code' => '34', 'amount' => 180000, 'data_size' => '7GB',
                'duration_days' => 2, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '165GB SME 2-Months@N50,000 60days', 'bundle_code' => '12', 'amount' => 5000000,
                'data_size' => '165GB', 'duration_days' => 60, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '35GB SME Monthly@N13,500 30days', 'bundle_code' => '12', 'amount' => 1350000,
                'data_size' => '35GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '360GB SME 3-Months@N3500 90days', 'bundle_code' => '12', 'amount' => 10000000,
                'data_size' => '360GB', 'duration_days' => 90, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '1TB SME 3-Months@N350,000 90days', 'bundle_code' => '12', 'amount' => 35000000,
                'data_size' => '1TB', 'duration_days' => 90, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '150GB 2-Month@N40,000 60days', 'bundle_code' => '9', 'amount' => 4000000,
                'data_size' => '150GB', 'duration_days' => 60, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '160GB 2-Month@N50000 60days', 'bundle_code' => '9', 'amount' => 5000000,
                'data_size' => '160GB', 'duration_days' => 60, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '480GB 3-Month@N90,000 90days', 'bundle_code' => '39', 'amount' => 9000000,
                'data_size' => '480GB', 'duration_days' => 90, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '500MB@N350 1-day', 'bundle_code' => '30', 'amount' => 35000, 'data_size' => '500MB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '110MB@N100 1-day', 'bundle_code' => '30', 'amount' => 10000, 'data_size' => '110MB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '230MB@N200 1-day', 'bundle_code' => '32', 'amount' => 20000, 'data_size' => '230MB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '1.5GB@N600 2-day', 'bundle_code' => '36', 'amount' => 60000, 'data_size' => '1.5GB',
                'duration_days' => 2, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '1.5GB@N600 7-days', 'bundle_code' => '31', 'amount' => 100000, 'data_size' => '1.5GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '2GB@N750 2-days', 'bundle_code' => '33', 'amount' => 75000, 'data_size' => '2GB',
                'duration_days' => 2, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '3.5GB@N1,500 7-days', 'bundle_code' => '32', 'amount' => 150000, 'data_size' => '3.5GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '16GB@N16,000 7-days', 'bundle_code' => '31', 'amount' => 1600000, 'data_size' => '16GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '2.5GB@N750 1-Day', 'bundle_code' => '34', 'amount' => 75000, 'data_size' => '2.5GB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'MTN', 'serviceCategoryId' => '6502eb6e65463b201bf8065f',
                'validity' => '20GB@N5,000 7days', 'bundle_code' => '34', 'amount' => 500000, 'data_size' => '20GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],


            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '75MB@N75 1day', 'bundle_code' => '74.91', 'amount' => 7500, 'data_size' => '75MB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '100MB@N100 1day', 'bundle_code' => '99.91', 'amount' => 10000, 'data_size' => '100MB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '200MB@N200 2day', 'bundle_code' => '199.91', 'amount' => 20000, 'data_size' => '200MB',
                'duration_days' => 2, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '1.5GB@N500 1day', 'bundle_code' => '499.91', 'amount' => 50000, 'data_size' => '1.5GB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '500MB@N500 7days', 'bundle_code' => '499.92', 'amount' => 50000, 'data_size' => '500MB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '1GB@N700 7days', 'bundle_code' => '799.91', 'amount' => 80000, 'data_size' => '1GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '3.5GB@N1500 7days', 'bundle_code' => '1499.92', 'amount' => 150000,
                'data_size' => '3.5GB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '2GB@N1,500 30days', 'bundle_code' => '1499.93', 'amount' => 150000, 'data_size' => '2GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '3GB@N2000 30days', 'bundle_code' => '1999.91', 'amount' => 200000, 'data_size' => '3GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '4GB@N2500 30days', 'bundle_code' => '2499.92', 'amount' => 250000, 'data_size' => '4GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '10GB@N4000 30days', 'bundle_code' => '3999.91', 'amount' => 400000,
                'data_size' => '10GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '13GB@N5000 30days', 'bundle_code' => '4999.92', 'amount' => 500000,
                'data_size' => '13GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '18GB@N6000 30days', 'bundle_code' => '5999.91', 'amount' => 600000,
                'data_size' => '18GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '25GB@N8000 30days', 'bundle_code' => '7999.91', 'amount' => 800000,
                'data_size' => '25GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '35GB@N10,000 30days', 'bundle_code' => '9999.91', 'amount' => 1000000,
                'data_size' => '35GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '60GB@N15,000 30days', 'bundle_code' => '14999.91', 'amount' => 1500000,
                'data_size' => '60GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '100GB@N20,000 30days', 'bundle_code' => '19999.91', 'amount' => 2000000,
                'data_size' => '100GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '160GB@30,000 30days', 'bundle_code' => '29999.91', 'amount' => 3000000,
                'data_size' => '160GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '210GB@N40,000 30days', 'bundle_code' => '39999.91', 'amount' => 4000000,
                'data_size' => '210GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '300GB@N50,000 30days', 'bundle_code' => '49999.91', 'amount' => 5000000,
                'data_size' => '300GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '350GB@N60,000 120days', 'bundle_code' => '59999.91', 'amount' => 6000000,
                'data_size' => '350GB', 'duration_days' => 120, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '650GB@N100,000 90days', 'bundle_code' => '99999.91', 'amount' => 10000000,
                'data_size' => '650GB', 'duration_days' => 90, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '300MB@N300 1day', 'bundle_code' => '299.91', 'amount' => 30000, 'data_size' => '300MB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '2GB+1GB YT Night + 200MB (YT, IG & Tiktok)@ @N600 2days', 'bundle_code' => '599.91',
                'amount' => 60000, 'data_size' => '3.2GB', 'duration_days' => 2, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '1.5GB@N1000 7days', 'bundle_code' => '999.92', 'amount' => 100000,
                'data_size' => '1.5GB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '6GB@N2500 7days', 'bundle_code' => '2499.91', 'amount' => 250000, 'data_size' => '6GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '10GB@N3000 7days', 'bundle_code' => '2999.91', 'amount' => 300000, 'data_size' => '10GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Airtel', 'serviceCategoryId' => '61efad12da92348f9dde5fa4',
                'validity' => '18GB@N5000 7days', 'bundle_code' => '4999.91', 'amount' => 500000, 'data_size' => '18GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],


            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '40MB + 5MB nite@N50 1day', 'bundle_code' => '50', 'amount' => 5000,
                'data_size' => '45MB', 'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '110MB + 15MB nite@N100 1day', 'bundle_code' => '100', 'amount' => 10000,
                'data_size' => '125MB', 'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '1.1GB + 1.5GB nite@N1000 1month', 'bundle_code' => '1000', 'amount' => 100000,
                'data_size' => '2.6GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '3.25GB + 3G nite@N2000 1month', 'bundle_code' => '2000', 'amount' => 200000,
                'data_size' => '6.25GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '4.5GB + 3GB nite@N2500 1month', 'bundle_code' => '2500', 'amount' => 250000,
                'data_size' => '7.5GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '8GB + 3GB nite@N3000 1month', 'bundle_code' => '3000', 'amount' => 300000,
                'data_size' => '11GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '11GB + 3GB nite@N4000 1month', 'bundle_code' => '4000', 'amount' => 400000,
                'data_size' => '14GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '14GB + 4GB nite@N5000 1month', 'bundle_code' => '5000', 'amount' => 500000,
                'data_size' => '18GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '36GB + 4GB Night@N10,000 1month', 'bundle_code' => '10000', 'amount' => 1000000,
                'data_size' => '40GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '65GB + 4GB Night@N15,000 1month', 'bundle_code' => '15000', 'amount' => 1500000,
                'data_size' => '69GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '105GB + 5GB Night@N20,000 1month', 'bundle_code' => '20000', 'amount' => 2000000,
                'data_size' => '110GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '1GB + 1GB Night@N500 1day', 'bundle_code' => '500', 'amount' => 50000,
                'data_size' => '2GB', 'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '220MB + 40MB Night@N200 2days', 'bundle_code' => '200', 'amount' => 20000,
                'data_size' => '260MB', 'duration_days' => 2, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '500MB Glo TV@N150 3days', 'bundle_code' => '150', 'amount' => 15000,
                'data_size' => '500MB', 'duration_days' => 3, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '1GB - Social Media@N300 3days', 'bundle_code' => '300', 'amount' => 30000,
                'data_size' => '1GB', 'duration_days' => 3, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '4GB + 2GB Night@N1500 7days', 'bundle_code' => '1500', 'amount' => 150000,
                'data_size' => '6GB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '2GB Glo TV@N450 7days', 'bundle_code' => '450', 'amount' => 45000, 'data_size' => '2GB',
                'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '2GB Glo TV - Lite@N900 7days', 'bundle_code' => '900', 'amount' => 90000,
                'data_size' => '2GB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '165GB Mega Plan@N30,000 30days', 'bundle_code' => '30000', 'amount' => 3000000,
                'data_size' => '165GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '220GB Mega Plan@N36,000 30days', 'bundle_code' => '36000', 'amount' => 3600000,
                'data_size' => '220GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '6GB Glo TV@N1400 30days', 'bundle_code' => '1400', 'amount' => 140000,
                'data_size' => '6GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '6GB Glo TV - Max@N3200 30days', 'bundle_code' => '3200', 'amount' => 320000,
                'data_size' => '6GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '320GB Mega Plan@N50,000 60days', 'bundle_code' => '50000', 'amount' => 5000000,
                'data_size' => '320GB', 'duration_days' => 60, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '380GB Mega Plan@N60,000 90days', 'bundle_code' => '60000', 'amount' => 6000000,
                'data_size' => '380GB', 'duration_days' => 90, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '475GB Mega Plan@N75,000 90days', 'bundle_code' => '75000', 'amount' => 7500000,
                'data_size' => '475GB', 'duration_days' => 90, 'is_amount_fixed' => true
            ],
            [
                'network_name' => 'Glo', 'serviceCategoryId' => '61efad06da92348f9dde5fa1',
                'validity' => '25GB + 4GB nite@N8000 1month', 'bundle_code' => '8000', 'amount' => 800000,
                'data_size' => '29GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],

            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '40MB@N50 1day', 'bundle_code' => '50', 'amount' => 5000, 'data_size' => '40MB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '83MB+50MB social@N100 1day', 'bundle_code' => '100', 'amount' => 10000,
                'data_size' => '133MB', 'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '150MB+100MB Night@N150 1day', 'bundle_code' => '150', 'amount' => 15000,
                'data_size' => '250MB', 'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '250MB@N200 1day', 'bundle_code' => '200', 'amount' => 20000, 'data_size' => '250MB',
                'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '1GB+100MB social@N300 1day', 'bundle_code' => '300', 'amount' => 30000,
                'data_size' => '1.1GB', 'duration_days' => 1, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '650MB+100MB Socials@N500 7days', 'bundle_code' => '500', 'amount' => 50000,
                'data_size' => '750MB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '3.4GB Special Data Plan@N1500 7days', 'bundle_code' => '1500', 'amount' => 150000,
                'data_size' => '3.4GB', 'duration_days' => 7, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '2GB Data Plan@N1000 30days', 'bundle_code' => '1000', 'amount' => 100000,
                'data_size' => '2GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '2.3GB Data Plan@N1200 30days', 'bundle_code' => '1200', 'amount' => 120000,
                'data_size' => '2.3GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '4.5GB Data Plan@N2000 30days', 'bundle_code' => '2000', 'amount' => 200000,
                'data_size' => '4.5GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '5.2GB Data Plan@N2500 30days', 'bundle_code' => '2500', 'amount' => 250000,
                'data_size' => '5.2GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '6.2GB Data Plan@N3000 30days', 'bundle_code' => '3000', 'amount' => 300000,
                'data_size' => '6.2GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '8.4GB Data Plan@N4000 30days', 'bundle_code' => '4000', 'amount' => 400000,
                'data_size' => '8.4GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '11.4GB Data Plan@N5000 30days', 'bundle_code' => '5000', 'amount' => 500000,
                'data_size' => '11.4GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '35GB@N7000 30days', 'bundle_code' => '7000', 'amount' => 700000, 'data_size' => '35GB',
                'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '50GB@N10,000 30days', 'bundle_code' => '10000', 'amount' => 1000000,
                'data_size' => '50GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '80GB@N15,000 30days', 'bundle_code' => '15000', 'amount' => 1500000,
                'data_size' => '80GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
            [
                'network_name' => '9mobile', 'serviceCategoryId' => '61efad1dda92348f9dde5fa7',
                'validity' => '125GB@N20,000 30days', 'bundle_code' => '20000', 'amount' => 2000000,
                'data_size' => '125GB', 'duration_days' => 30, 'is_amount_fixed' => true
            ],
        ];

        DB::table('safehaven_data_bundles')->insert($bundles);
    }

    public function importSafehavenTvBundles()
    {
        $bundles = [
            [
                "name" => "DStv Compact Plus valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_comple36",
                "amount" => 30000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "DStv Compact valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_compe36",
                "amount" => 19000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "DStv Confam valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_nnj2e36",
                "amount" => 11000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "DStv Indian valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_asiae36",
                "amount" => 14900,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "DStv Padi valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_nltese36",
                "amount" => 4400,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "DStv Premium + French valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_prwfrnse36",
                "amount" => 69000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "DStv Premium W/Afr + Asian Bouquet E36 valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_prwasie36",
                "amount" => 50500,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "DStv Premium valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_prwe36",
                "amount" => 44500,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "DStv Yanga valid for 1 month",
                "serviceCategoryId" => "61efad38da92348f9dde5faa",
                "network_code" => "dstv",
                "bundleCode" => "ng_dstv_nnj1e36",
                "amount" => 6000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "GOtv Jinja valid for 1 month",
                "serviceCategoryId" => "61efad45da92348f9dde5fad",
                "network_code" => "gotv",
                "bundleCode" => "ng_gotv_gotvnj1",
                "amount" => 3900,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "GOtv Jolli valid for 1 month",
                "serviceCategoryId" => "61efad45da92348f9dde5fad",
                "network_code" => "gotv",
                "bundleCode" => "ng_gotv_gotvnj2",
                "amount" => 5800,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "GOtv Max valid for 1 month",
                "serviceCategoryId" => "61efad45da92348f9dde5fad",
                "network_code" => "gotv",
                "bundleCode" => "ng_gotv_gotvmax",
                "amount" => 8500,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "GOtv Smallie - quarterly valid for 1 month",
                "serviceCategoryId" => "61efad45da92348f9dde5fad",
                "network_code" => "gotv",
                "bundleCode" => "ng_gotv_golite",
                "amount" => 5100,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "GOtv Smallie-monthly valid for 1 month",
                "serviceCategoryId" => "61efad45da92348f9dde5fad",
                "network_code" => "gotv",
                "bundleCode" => "ng_gotv_gohan",
                "amount" => 1900,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "GOtv Smallie-yearly valid for 1 month",
                "serviceCategoryId" => "61efad45da92348f9dde5fad",
                "network_code" => "gotv",
                "bundleCode" => "ng_gotv_goltanl",
                "amount" => 15000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "GOtv Supa valid for 1 month",
                "serviceCategoryId" => "61efad45da92348f9dde5fad",
                "network_code" => "gotv",
                "bundleCode" => "ng_gotv_gotvsupa",
                "amount" => 11400,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "GOtv Supa Plus Bouquet valid for 1 month",
                "serviceCategoryId" => "61efad45da92348f9dde5fad",
                "network_code" => "gotv",
                "bundleCode" => "ng_gotv_gotvsupaplus",
                "amount" => 16800,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Basic (Antenna) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "basic",
                "amount" => 4000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Basic (Antenna) - Weekly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "basicweek",
                "amount" => 1400,
                "duration" => 7,
                "isAmountFixed" => true
            ],
            [
                "name" => "Basic (Dish) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "smart",
                "amount" => 5100,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Basic (Dish) - Weekly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "smartweek",
                "amount" => 1700,
                "duration" => 7,
                "isAmountFixed" => true
            ],
            [
                "name" => "Classic (Antenna) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "classic",
                "amount" => 6000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Classic (Antenna) - Weekly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "classicweek",
                "amount" => 2000,
                "duration" => 7,
                "isAmountFixed" => true
            ],
            [
                "name" => "Classic (Dish) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "special",
                "amount" => 7400,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Classic (Dish) - Weekly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "specialweek",
                "amount" => 2500,
                "duration" => 7,
                "isAmountFixed" => true
            ],
            [
                "name" => "Nova (Antenna) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "nova",
                "amount" => 2100,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Nova (Antenna) - Weekly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "novaweek",
                "amount" => 700,
                "duration" => 7,
                "isAmountFixed" => true
            ],
            [
                "name" => "Nova (Dish) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "novadish",
                "amount" => 2100,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Nova (Dish) - Weekly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "novadishweek",
                "amount" => 700,
                "duration" => 7,
                "isAmountFixed" => true
            ],
            [
                "name" => "Startimes Chinese (Dish) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "chinese",
                "amount" => 21000,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Super (Antenna) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "super-antenna",
                "amount" => 9500,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Super (Antenna) - Weekly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "superweek-antenna",
                "amount" => 3200,
                "duration" => 7,
                "isAmountFixed" => true
            ],
            [
                "name" => "Super (Dish) - Monthly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "super",
                "amount" => 9800,
                "duration" => 30,
                "isAmountFixed" => true
            ],
            [
                "name" => "Super (Dish) - Weekly",
                "serviceCategoryId" => "61efad50da92348f9dde5fb0",
                "network_code" => "startimes",
                "bundleCode" => "superweek",
                "amount" => 3300,
                "duration" => 7,
                "isAmountFixed" => true
            ]
        ];

        return DB::table('safehaven_tv_bundles')->insert($bundles);
    }

    public function importSafeHavenAirtimeProviders()
    {
        $providers = [
            [
                "providerCommission" => null,
                "_id" => "61efacbcda92348f9dde5f92",
                "name" => "MTN",
                "identifier" => "MTN",
                "service" => "61efaba1da92348f9dde5f6c",
                "vendor" => "687759c5bb42b8f020c309d2",
                "isFixedAmount" => false,
                "description" => "Mtn Airtime",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646209736/SafeHavenVAS/New-mtn-logo_u1dy66.jpg",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efacd3da92348f9dde5f98",
                "name" => "AIRTEL",
                "identifier" => "AIRTEL",
                "service" => "61efaba1da92348f9dde5f6c",
                "vendor" => "687759c5bb42b8f020c309d2",
                "isFixedAmount" => false,
                "description" => "Airtel Airtime",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208565/SafeHavenVAS/Airtel-Logo-Vector_1_hwmiic.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efacdeda92348f9dde5f9b",
                "name" => "9Mobile",
                "identifier" => "ETISALAT",
                "service" => "61efaba1da92348f9dde5f6c",
                "vendor" => "687759c5bb42b8f020c309d2",
                "isFixedAmount" => false,
                "description" => "9Mobile Airtime",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208565/SafeHavenVAS/download_1_y1c4lr.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efacc8da92348f9dde5f95",
                "name" => "GLO",
                "identifier" => "GLO",
                "service" => "61efaba1da92348f9dde5f6c",
                "vendor" => "687759c5bb42b8f020c309d2",
                "isFixedAmount" => false,
                "description" => "Glo Airtime",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208567/SafeHavenVAS/kindpng_4648442_1_ibibke.png",
            ]
        ];

        return DB::table('safehaven_airtime_providers')->insert($providers);

    }

    public function importSafeHavenUtilityProviders()
    {
        $providers = [
            [
                "providerCommission" => null,
                "_id" => "61efac19b5ce7eaad3b405d4",
                "name" => "BEDC",
                "identifier" => "BENIN",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Benin Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208565/SafeHavenVAS/bedc_transparent_logo_1_vortpx.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac27da92348f9dde5f74",
                "name" => "EKEDC",
                "identifier" => "EKO",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Eko Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208567/SafeHavenVAS/ekedc_logo_1_gvanzy.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac35da92348f9dde5f77",
                "name" => "AEDC",
                "identifier" => "ABUJA",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Abuja Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208565/SafeHavenVAS/aedc_logo_1_u4foxr.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac42da92348f9dde5f7a",
                "name" => "EEDC",
                "identifier" => "ENUGU",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Enugu Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208566/SafeHavenVAS/eedc_logo_1_pggere.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac51da92348f9dde5f7d",
                "name" => "IBEDC",
                "identifier" => "IBADAN",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Ibadan Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208568/SafeHavenVAS/ibedc_1_zquagj.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac5eda92348f9dde5f80",
                "name" => "IKEDC",
                "identifier" => "IKEJA",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Ikeja Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208566/SafeHavenVAS/Ikeja-Electric-Logo-new-1_1_xzufx0.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac6ada92348f9dde5f83",
                "name" => "JEDC",
                "identifier" => "JOS",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "JOS Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208567/SafeHavenVAS/Jos-Electricity-Distribution-Company_1_ybqwmz.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac78da92348f9dde5f86",
                "name" => "KAEDC",
                "identifier" => "KADUNA",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Kaduna Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208565/SafeHavenVAS/34-341783_kaduna-electricity-distribution-company-kaduna-electricity-distribution-company_1_cvxnol.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac87da92348f9dde5f89",
                "name" => "KEDCO",
                "identifier" => "KANO",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Kano Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208567/SafeHavenVAS/Kedco_Logo_web_1_hbbhfj.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efac94da92348f9dde5f8c",
                "name" => "PHEDC",
                "identifier" => "PH",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "PortHarcourt Electricity Distribution Company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208567/SafeHavenVAS/PHED_1_rpevjz.png",
            ],
            [
                "providerCommission" => null,
                "_id" => "61efaca1da92348f9dde5f8f",
                "name" => "YEDC",
                "identifier" => "YOLA",
                "service" => "61efab78b5ce7eaad3b405d0",
                "vendor" => "68ad85f93f9cb0a23bb0eee8",
                "isFixedAmount" => false,
                "description" => "Yola Electricity distribution company",
                "logoUrl" => "https://res.cloudinary.com/sudo-africa/image/upload/v1646208568/SafeHavenVAS/yedc_logo_2_cgumad.png",
            ]
        ];

        return DB::table('safehaven_utilities_providers')->insert($providers);

    }

    public function verifyUtility(string $entityNumber, string $serviceCategoryId): array
    {
        $payload = [
            'entityNumber' => $entityNumber,
            'serviceCategoryId' => $serviceCategoryId,
        ];

        Log::info('SafeHavenService - verifying utility', [
            'customerId' => $entityNumber,
            'serviceCategoryId' => $serviceCategoryId,
        ]);

        try {
            return $this->sendPost('/vas/verify', $payload);
        } catch (\Throwable $e) {
            Log::error('SafeHavenService - utility verification failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            return [
                'status' => 500,
                'error' => $e->getMessage(),
            ];
        }
    }
}
