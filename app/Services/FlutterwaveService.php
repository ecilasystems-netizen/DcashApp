<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected $baseUrl;
    protected $secretKey;

    public function __construct()
    {

        $this->secretKey = config('services.flutterwave.secret_key');
        $this->baseUrl = config('services.flutterwave.base_url');

    }

    public function verifyNigerianBankAccount($accountNumber, $bankCode)
    {
        try {
            $response = $this->sendFlutterwaveRequest('/accounts/resolve', [
                'account_number' => $accountNumber,
                'account_bank' => $bankCode,
            ], 'POST');

            if ($response->successful() && $response->json('status') === 'success') {

                Log::error('Flutterwave account: '.$response);

                return [
                    'success' => true,
                    'data' => [
                        'account_name' => $response->json('data.account_name'),
                        'account_number' => $response->json('data.account_number'),
                    ]
                ];
            }


            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Unable to verify account'
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave account verification failed: '.$e->getMessage());
            return [
                'success' => false,
                'message' => 'Account verification failed. Please try again.'
            ];
        }
    }

    protected function sendFlutterwaveRequest($endpoint, $payload = [], $method = 'GET')
    {
        try {
            $http = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->secretKey,
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ]);

            $url = $this->baseUrl.$endpoint;

            if (strtoupper($method) === 'POST') {
                $response = $http->post($url, $payload);
            } else {
                $response = $http->get($url, $payload);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Flutterwave API request failed: '.$e->getMessage());
            return null;
        }
    }

    public function makeTransfer($transferData)
    {
        try {
            $response = $this->sendFlutterwaveRequest('/transfers', $transferData, 'POST');

            if ($response && $response->successful() && $response->json('status') === 'success') {
                return [
                    'success' => true,
                    'data' => $response->json('data')
                ];
            }

            return [
                'success' => false,
                'message' => $response ? $response->json('message') : 'Unable to make transfer'
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave transfer failed: '.$e->getMessage());
            return [
                'success' => false,
                'message' => 'Transfer failed. Please try again.'
            ];
        }
    }
}
