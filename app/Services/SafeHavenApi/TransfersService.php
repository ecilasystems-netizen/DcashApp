<?php

namespace App\Services\SafeHavenApi;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransfersService
{
    protected AuthentificationService $authService;
    protected string $baseApi;
    protected array $headers;
    protected string $safehaven_debit_account_number;

    public function __construct(AuthentificationService $authService)
    {
        $this->authService = $authService;
        $this->baseApi = $authService->getBaseApi();
        $this->headers = $authService->getHeaders();
        $this->safehaven_debit_account_number = config('safehaven.debit_account_number');

    }

    protected function sendPost(string $endpoint, array $payload, bool $retry = true): array
    {
        Log::info('TransfersService - request', [
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
                Log::warning('TransfersService - json decode failed', [
                    'body' => $body,
                    'error' => $e->getMessage()
                ]);
            }

            if (is_array($json) && isset($json['statusCode']) && is_numeric($json['statusCode'])) {
                $status = (int) $json['statusCode'];
            }

            Log::info('TransfersService - response', [
                'status' => $status,
                'body' => $body,
                'json' => $json
            ]);

            if ($status === 403 && $retry) {
                Log::warning('TransfersService - token expired, refreshing');

                try {
                    $this->authService->refreshToken();
                    $this->headers = $this->authService->getHeaders();
                    Log::info('TransfersService - retrying request with new token');

                    return $this->sendPost($endpoint, $payload, false);

                } catch (\Exception $e) {
                    Log::error('TransfersService - token refresh failed', [
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
                Log::error('TransfersService - request failed', [
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
            Log::error('TransfersService - request exception', [
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

        Log::info('TransfersService - name enquiry', [
            'accountNumber' => $accountNumber,
            'bankCode' => $bankCode
        ]);

        $response = $this->sendPost('/transfers/name-enquiry', $payload);

        if (in_array($response['status'], [200, 201]) && isset($response['json']['data'])) {
            Log::info('TransfersService - name enquiry successful', [
                'accountName' => $response['json']['data']['accountName'] ?? null,
                'kycLevel' => $response['json']['data']['kycLevel'] ?? null
            ]);
        } else {
            Log::error('TransfersService - name enquiry failed', [
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
            'beneficiaryBankCode' => $data['beneficiaryBankCode'],
            'beneficiaryAccountNumber' => $data['beneficiaryAccountNumber'],
            'amount' => $data['amount'],
            'saveBeneficiary' => $data['safeBeneficiary'] ?? false,
            'narration' => $data['narration'] ?? '',
            'paymentReference' => $data['paymentReference'] ?? null,
        ];

        if (isset($data['saveBeneficiary'])) {
            $payload['saveBeneficiary'] = $data['saveBeneficiary'];
        }

        if (isset($data['beneficiaryName'])) {
            $payload['beneficiaryName'] = $data['beneficiaryName'];
        }

        Log::info('TransfersService - initiating transfer', [
            'debitAccount' => $data['debitAccountNumber'],
            'amount' => $data['amount'],
            'nameEnquiryRef' => $data['nameEnquiryReference']
        ]);

        $response = $this->sendPost('/transfers', $payload);

        if (in_array($response['status'], [200, 201]) && isset($response['json']['data'])) {
            Log::info('TransfersService - transfer successful', [
                'sessionId' => $response['json']['data']['sessionId'] ?? null,
                'status' => $response['json']['data']['status'] ?? null,
                'amount' => $response['json']['data']['amount'] ?? null
            ]);
        } else {
            Log::error('TransfersService - transfer failed', [
                'status' => $response['status'],
                'message' => $response['json']['message'] ?? 'Unknown error'
            ]);
        }

        return $response;
    }

    public function getTransferStatus(string $sessionId): array
    {
        $endpoint = "/transfers/{$sessionId}";

        Log::info('TransfersService - getting transfer status', [
            'sessionId' => $sessionId
        ]);

        try {
            $response = Http::withHeaders($this->headers)
                ->get($this->baseApi.$endpoint);

            $status = $response->status();
            $json = $response->json();

            Log::info('TransfersService - transfer status response', [
                'status' => $status,
                'transferStatus' => $json['data']['status'] ?? null
            ]);

            return [
                'status' => $status,
                'body' => $response->body(),
                'json' => $json,
            ];

        } catch (\Exception $e) {
            Log::error('TransfersService - get transfer status exception', [
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
