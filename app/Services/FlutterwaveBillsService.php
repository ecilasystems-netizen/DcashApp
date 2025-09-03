<?php

namespace App\Services;

use App\Models\FlutterwaveBillsItem;
use Illuminate\Support\Facades\Http;

class FlutterwaveBillsService
{
    protected $baseUrl;
    protected $secretKey;

    // Constructor to initialize Flutterwave API credentials from config
    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->baseUrl = config('services.flutterwave.base_url', 'https://api.flutterwave.com/v3');
    }

    // Retrieve top bill categories for a given country
    public function getTopBillCategories($country = 'NG')
    {
        if (empty($country)) {
            return $this->validationError('country is required');
        }
        return $this->makeFlutterwaveRequest('top-bill-categories', ['country' => $country]);
    }

    // Return a standardized validation error response
    protected function validationError($message)
    {
        return [
            'status' => 'error',
            'message' => 'Validation error: '.$message,
        ];
    }

    // Make an HTTP request to the Flutterwave API with given parameters
    protected function makeFlutterwaveRequest($endpoint, $queryParams = [], $method = 'GET', $data = [])
    {
        $url = $this->baseUrl.'/'.$endpoint;

        try {
            \Log::info("Flutterwave API Request", [
                'endpoint' => $endpoint,
                'method' => $method,
                'queryParams' => $queryParams,
                'data' => $method === 'GET' ? [] : $data
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->$method($url, $method === 'GET' ? $queryParams : $data);

            $responseData = $response->json();

            // Log response status and data
            if (!$response->successful()) {
                \Log::error("Flutterwave API Error", [
                    'endpoint' => $endpoint,
                    'statusCode' => $response->status(),
                    'response' => $responseData
                ]);
            } else {
                \Log::info("Flutterwave API Success", [
                    'endpoint' => $endpoint,
                    'statusCode' => $response->status()
                ]);
            }

            return $responseData;
        } catch (\Exception $e) {
            // Log detailed exception information
            \Log::error("Flutterwave API Exception", [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    // Validate a customer for a specific bill item
    public function validateCustomer($billItem, $customer)
    {

        if (empty($billItem)) {
            return $this->validationError('billItem is required');
        }
        if (empty($customer)) {
            return $this->validationError('customer is required');
        }
        $endpoint = "bill-items/{$billItem}/validate";
        $queryParams = ['customer' => $customer];
        return $this->makeFlutterwaveRequest($endpoint, $queryParams);
    }

    // Create a bill payment for a specific biller and item
    public function createBillPayment(
        $billerId,
        $itemId,
        $country,
        $customerId,
        $amount,
        $reference,
        $type = null,
        $isDataPurchase = false,
        $callbackUrl = 'https://webhook.site/your-webhook-url',


    ) {
        if (empty($billerId)) {
            return $this->validationError('billerId is required');
        }
        if (empty($itemId)) {
            return $this->validationError('itemId is required');
        }
        if (empty($country)) {
            return $this->validationError('country is required');
        }
        if (empty($customerId)) {
            return $this->validationError('customerId is required');
        }
        if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
            return $this->validationError('amount must be a positive number');
        }
        if (empty($reference)) {
            return $this->validationError('reference is required');
        }
        if (empty($callbackUrl)) {
            return $this->validationError('callbackUrl is required');
        }

        $endpoint = "billers/{$billerId}/items/{$itemId}/payment";
        $data = [
            'country' => $country,
            'customer_id' => $customerId,
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => $callbackUrl
        ];

        // Add type field for data purchases
        if ($isDataPurchase) {
            $data['type'] = $type;
        }

        return $this->makeFlutterwaveRequest($endpoint, [], 'POST', $data);
    }

    // Get all bill items for a specific biller
    public function getBillerItems($billerId)
    {
        if (empty($billerId)) {
            return $this->validationError('billerId is required');
        }

        // If the function is just returning the query result directly
        $items = FlutterwaveBillsItem::where('biller_code', $billerId)->get();

        return [
            "status" => 'success',
            "message" => "Bill items fetched successfully",
            "data" => $items->toArray()
        ];
//        return $this->makeFlutterwaveRequest("billers/{$billerId}/items");
    }

    // Retrieve billers for a given category and country
    public function getBillers($category, $country)
    {
        if (empty($category)) {
            return $this->validationError('category is required');
        }
        if (empty($country)) {
            return $this->validationError('country is required');
        }
        return $this->makeFlutterwaveRequest('bills/'.$category.'/billers', ['country' => $country]);
    }

    // Get the status of a bill payment by reference
    public function getBillStatus($reference, $verbose = 1)
    {
        if (empty($reference)) {
            return $this->validationError('billId is required');
        }
        $endpoint = "bills/{$reference}";
        $queryParams = ['verbose' => $verbose];
        return $this->makeFlutterwaveRequest($endpoint, $queryParams);
    }
}
