<?php

declare(strict_types=1);

namespace App\Services\SafeHavenApi;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthentificationService
{
    private const TOKEN_CACHE_KEY = 'safehaven_api_token';
    private const TOKEN_EXPIRY_KEY = 'safehaven_token_expiry';
    private const TOKEN_REFRESH_BUFFER_MINUTES = 5;

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
            'Authorization' => "Bearer {$this->getValidToken()}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get a valid token, refreshing if necessary
     */
    public function getValidToken(): string
    {
        $token = Cache::get(self::TOKEN_CACHE_KEY);
        $expiresAt = Cache::get(self::TOKEN_EXPIRY_KEY);

        // Refresh if token doesn't exist or expires in less than 5 minutes
        if (!$token || !$expiresAt || now()->addMinutes(self::TOKEN_REFRESH_BUFFER_MINUTES)->isAfter($expiresAt)) {
            Log::info('SafeHavenApi - token expired or near expiry, refreshing proactively');
            return $this->refreshAndCacheToken();
        }

        return $token;
    }

    /**
     * Refresh token and cache it
     */
    public function refreshToken(): string
    {
        return $this->refreshAndCacheToken();
    }

    /**
     * Internal method to refresh and cache the token
     */
    private function refreshAndCacheToken(): string
    {
        try {
            $payload = [
                'client_id' => $this->clientId,
                'grant_type' => 'client_credentials',
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => $this->client_assertion,
            ];

            $response = Http::timeout(10)->post($this->baseApi . '/oauth2/token', $payload);

            $status = $response->status();
            $json = $response->json();

            if ($status === 201 && isset($json['access_token'])) {
                $token = $json['access_token'];
                $expiresIn = $json['expires_in'] ?? 3600; // Default to 1 hour if not provided

                // Cache token until expiration
                $expiresAt = now()->addSeconds($expiresIn);
                Cache::put(self::TOKEN_CACHE_KEY, $token, $expiresAt);
                Cache::put(self::TOKEN_EXPIRY_KEY, $expiresAt, $expiresAt);

                Log::info('SafeHavenApi - token refreshed and cached', [
                    'expires_in' => $expiresIn,
                    'expires_at' => $expiresAt->toDateTimeString()
                ]);

                $this->bearerToken = $token;
                return $token;
            }

            $errorMsg = $json['message'] ?? json_encode($json);
            Log::error('SafeHavenApi - token refresh failed', [
                'status' => $status,
                'response' => $errorMsg
            ]);

            throw new \Exception("Token refresh failed with status {$status}: {$errorMsg}");

        } catch (\Exception $e) {
            Log::error('AuthentificationService - token refresh exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Clear cached token (useful for testing or forced refresh)
     */
    public function clearCachedToken(): void
    {
        Cache::forget(self::TOKEN_CACHE_KEY);
        Cache::forget(self::TOKEN_EXPIRY_KEY);
        Log::info('SafeHavenApi - cached token cleared');
    }

    public function getBearerToken(): string
    {
        return $this->getValidToken();
    }

    public function getBaseApi(): string
    {
        return $this->baseApi;
    }
}
