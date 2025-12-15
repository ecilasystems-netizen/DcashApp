<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\CurrencyPair;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NoonesExchangeRatesService
{


    public function __construct()
    {
    }

    public function autoUpdateRates()
    {
        $pairs = [

            'PHP-NGN',
            'USDT-NGN',
            'USDT-PHP',
            'USDC-NGN',
            'USDC-PHP',
            'USDT-USDC',
        ];

        foreach ($pairs as $pair) {
            try {
                [$baseCurrency, $quoteCurrency] = explode('-', $pair);

                // Get currency IDs from the database
                $baseCurrencyModel = Currency::where('code', $baseCurrency)->first();
                $quoteCurrencyModel = Currency::where('code', $quoteCurrency)->first();

                if (!$baseCurrencyModel || !$quoteCurrencyModel) {
                    Log::warning("Currency not found for pair: {$pair}");
                    continue;
                }

                switch ($pair) {
                    case 'PHP-NGN':
                        $baseCurrencyRate = $this->getCurrencyRate($baseCurrency);
                        $quoteCurrencyRate = $this->getCurrencyRate($quoteCurrency);

                        $getBuyRate = $quoteCurrencyRate / $baseCurrencyRate;
                        $buyRate = $getBuyRate - 0.2;

                        $getSellRate = $quoteCurrencyRate / $baseCurrencyRate;
                        $sellRate = $getSellRate + 0.8;
                        break;


                    case 'USDT-NGN':
                        $quoteCurrencyRate = $this->getCurrencyRate($quoteCurrency);

                        $buyRate = $quoteCurrencyRate;
                        $sellRate = $quoteCurrencyRate + 30;
                        break;

                    case 'USDT-PHP':
                        $quoteCurrencyRate = $this->getCurrencyRate($quoteCurrency);

                        $buyRate = $quoteCurrencyRate - 0.6;
                        $sellRate = $quoteCurrencyRate + 0.9;
                        break;
                    case 'USDC-NGN':
                        $quoteCurrencyRate = $this->getCurrencyRate($quoteCurrency);

                        $buyRate = $quoteCurrencyRate;
                        $sellRate = $quoteCurrencyRate + 30;
                        break;
                    case 'USDC-PHP':
                        $quoteCurrencyRate = $this->getCurrencyRate($quoteCurrency);

                        $buyRate = $quoteCurrencyRate - 0.6;
                        $sellRate = $quoteCurrencyRate + 0.9;
                        break;

                    case 'USDT-USDC':
                        $quoteCurrencyRate = $this->getCurrencyRate('USD');

                        $buyRate = $quoteCurrencyRate;
                        $sellRate = $quoteCurrencyRate;
                        break;
                }

                $this->updatePairRates($buyRate, $sellRate, $baseCurrencyModel->id, $quoteCurrencyModel->id);

                \Log::info("Updated rates for pair: {$pair}");

            } catch (Exception $e) {
                \Log::error("Failed to update rates for pair {$pair}: ".$e->getMessage());
            }
        }
    }

    public function updatePairRates($buyRate, $sellRate, $baseCurrencyId, $quoteCurrencyId)
    {
        // Get pair ids where
        // pair 1: base_currency_id = $baseCurrencyId and quote_currency_id = $quoteCurrencyId
        $pair1 = CurrencyPair::where('base_currency_id', $baseCurrencyId)
            ->where('quote_currency_id', $quoteCurrencyId)
            ->where('auto_update', 1)
            ->first();

        // pair 2: base_currency_id = $quoteCurrencyId and quote_currency_id = $baseCurrencyId
        $pair2 = CurrencyPair::where('base_currency_id', $quoteCurrencyId)
            ->where('quote_currency_id', $baseCurrencyId)
            ->where('auto_update', 1)
            ->first();

        $updatedPairs = [];

        // For pair 1, rate and rawRate is $buyRate
        if ($pair1) {
            $pair1->update([
                'rate' => $buyRate,
                'raw_rate' => $buyRate,
            ]);
            $updatedPairs[] = $pair1->id;
        }

        // For pair 2, rate is 1/$sellRate and rawRate is $sellRate
        if ($pair2 && $sellRate > 0) {
            $pair2->update([
                'rate' => $sellRate,
                'raw_rate' => 1 / $sellRate,
            ]);
            $updatedPairs[] = $pair2->id;
        }

        return response()->json([
            'success' => true,
            'message' => 'Currency pairs updated successfully',
            'updated_pairs' => $updatedPairs,
            'pairs_count' => count($updatedPairs),
        ], 200);
    }

    public function getCurrencyRate($currencyCode)
    {
        // Step 1: Retrieve OAuth token
        $tokenResponse = $this->getAccessToken();
        if (isset($tokenResponse->original['error'])) {
            return $tokenResponse; // Bubble up any token error
        }

        // Convert JsonResponse to an array
        $tokenData = $tokenResponse->getData(true);

        // Extract the access_token
        $accessToken = $tokenData['access_token'];

        // Step 2: Call the currency list endpoint
        $response = Http::withToken($accessToken)
            ->post('https://api.noones.com/noones/v1/currency/list');

        if ($response->failed()) {
            return response()->json([
                'error' => 'Service call failed',
                'details' => $response->json(),
            ], 400);
        }

        // Parse the response into a PHP array
        $currencyData = $response->json();

        // Step 3: Search for the requested currency code NG, PHP only supported
        if (!empty($currencyData['data']['currencies'])) {
            foreach ($currencyData['data']['currencies'] as $currency) {
                if (isset($currency['code']) && $currency['code'] === $currencyCode) {
                    // Return the requested->code currency object as JSON
                    return (float) $currency['rate']['usdt'];
                }
            }
        }

        // If currency code is not found, return an error or handle as you wish
        return response()->json([
            'error' => $currencyCode.' currency not found',
        ], 404);
    }

    /**
     * Returns the entire token response as an array.
     */
    public function getAccessToken()
    {
        $clientId = env('SERVICES_NOONES_CLIENT_ID');
        $clientSecret = env('SERVICES_NOONES_CLIENT_SECRET');

        // Validate credentials exist
        if (empty($clientId) || empty($clientSecret)) {
            return response()->json([
                'error' => 'Missing credentials',
                'message' => 'Client ID or Client Secret not configured',
            ], 500);
        }

        $url = 'https://auth.noones.com/oauth2/token';

        // Build the form data
        $postData = http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'User-Agent: MyApp/1.0',
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
            curl_close($ch);

            return response()->json([
                'error' => 'cURL error occurred',
                'message' => $errorMsg,
            ], 500);
        }

        // Retrieve HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Handle 401 specifically for invalid credentials
        if ($httpCode === 401) {
            Log::error('Noones API authentication failed', [
                'client_id' => $clientId,
                'response' => $response,
            ]);

            return response()->json([
                'error' => 'Authentication failed',
                'message' => 'Invalid client credentials. Please verify your Client ID and Secret.',
                'details' => json_decode($response, true),
            ], 401);
        }

        // Handle other HTTP errors
        if ($httpCode >= 400) {
            return response()->json([
                'error' => 'Request failed',
                'status' => $httpCode,
                'details' => $response,
            ], $httpCode);
        }

        // Attempt to decode the JSON response
        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error' => 'Invalid JSON response',
                'details' => $response,
            ], 500);
        }

        // Success! Return the token data
        return response()->json($decoded);
    }
}
