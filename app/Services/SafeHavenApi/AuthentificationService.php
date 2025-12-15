<?php

namespace App\Services\SafeHavenApi;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthentificationService
{
    protected string $clientId;
    protected string $bearerToken;
    protected string $baseApi;
    protected string $client_assertion;

    public function __construct()
    {
        $this->clientId = config('safehaven.client_id');
        $this->bearerToken = config('safehaven.bearer_token');
        $this->baseApi = config('safehaven.base_url');
        $this->client_assertion = config('safehaven.client_assertion');
    }

    public function getHeaders(): array
    {
        return [
            'ClientID' => $this->clientId,
            'Authorization' => "Bearer {$this->bearerToken}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function refreshToken(): string
    {
        Log::info('AuthentificationService - refreshing token');

        try {
            $payload = [
                'client_id' => 'abe81d155813ee90e04c848c2d1bc2a7',
                'grant_type' => 'client_credentials',
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => $this->client_assertion,
            ];

            Log::info('AuthentificationService - token request', ['payload' => $payload]);

            $response = Http::post($this->baseApi.'/oauth2/token', $payload);

            $status = $response->status();
            $json = $response->json();

            Log::info('AuthentificationService - token response', [
                'status' => $status,
                'has_access_token' => isset($json['access_token']),
                'response' => $json
            ]);

            if ($status === 201 && isset($json['access_token'])) {
                $this->bearerToken = $json['access_token'];

                Log::info('AuthentificationService - token refreshed successfully');

                return $this->bearerToken;
            }

            $errorMsg = $json['message'] ?? json_encode($json);
            Log::error('AuthentificationService - token refresh failed', [
                'status' => $status,
                'error' => $errorMsg
            ]);

            throw new \Exception("Token refresh failed with status {$status}");

        } catch (\Exception $e) {
            Log::error('AuthentificationService - token refresh exception', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    public function getBaseApi(): string
    {
        return $this->baseApi;
    }
}
