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


            if ($status === 403 && $retry) {
                Log::warning('TransfersService - token expired, refreshing');

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

        $response = $this->sendPost('/transfers/name-enquiry', $payload);

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


        $response = $this->sendPost('/transfers', $payload);

        return $response;
    }

    public function getTransferStatus(string $sessionId): array
    {
        $endpoint = "/transfers/{$sessionId}";

        try {
            $response = Http::withHeaders($this->headers)
                ->get($this->baseApi.$endpoint);

            $status = $response->status();
            $json = $response->json();

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
