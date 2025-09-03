<?php

namespace App\Http\Controllers\Api\Flutterwave;

use App\Http\Controllers\Controller;
use App\Services\FlutterwaveBillsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FlutterwaveBillsController extends Controller
{
    protected $flutterwaveService;

    // Bill type configurations
    protected array $billTypes = [
        'airtime' => [
            'category' => 'AIRTIME',
            'type' => 'AIRTIME_PURCHASE',
            'min_amount' => 50,
            'max_amount' => 10000,
            'networks' => ['MTN', 'AIRTEL', 'GLO', '9MOBILE'],
        ],
        'data' => [
            'category' => 'DATA_BUNDLE',
            'type' => 'DATA_PURCHASE',
            'min_amount' => 100,
            'max_amount' => 20000,
            'networks' => ['MTN', 'AIRTEL', 'GLO', '9MOBILE'],
        ],
        'cable_tv' => [
            'category' => 'CABLE_TV',
            'type' => 'CABLE_TV_SUBSCRIPTION',
            'min_amount' => 1000,
            'max_amount' => 50000,
            'providers' => ['DSTV', 'GOTV', 'STARTIMES', 'TSTV'],
        ],
        'electricity' => [
            'category' => 'ELECTRICITY',
            'type' => 'ELECTRICITY_BILL',
            'min_amount' => 500,
            'max_amount' => 100000,
            'providers' => ['EKEDC', 'IKEDC', 'AEDC', 'PHEDC', 'BEDC', 'YEDC'],
        ],
        'internet' => [
            'category' => 'INTERNET',
            'type' => 'INTERNET_SUBSCRIPTION',
            'min_amount' => 1000,
            'max_amount' => 50000,
            'providers' => ['SMILE', 'SPECTRANET', 'SWIFT', 'IPNX'],
        ],
    ];

    public function __construct(FlutterwaveBillsService $flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
    }

    public function getBillPaymentStatus(Request $request, $reference)
    {
        $verbose = $request->query('verbose', 1);
        $result = $this->flutterwaveService->getBillStatus($reference, $verbose);
        return response()->json($result);
    }

    public function getTopBillCategories()
    {
        $response = $this->flutterwaveService->getTopBillCategories();
        return response()->json($response);
    }

    public function validateCustomerDetails(Request $request)
    {
        $billItem = $request->input('bill_item', 'AT099');
        $customer = $request->input('customer', '08038291822');
        $response = $this->flutterwaveService->validateCustomer($billItem, $customer);
        return response()->json($response);
    }

    public function createBillPayment(Request $request)
    {
        $billerId = $request->input('biller_id', 'BIL099');
        $itemId = $request->input('item_id', 'AT099');
        $country = $request->input('country', 'NG');
        $customerId = $request->input('customer_id', '+23490803840303');
        $amount = $request->input('amount', 500);
        $reference = (string) Str::random(16);
        $callbackUrl = $request->input('callback_url', 'https://webhook.site/5f9a659a-11a2-4925-89cf-8a59ea6a019a');

        $validationResponse = $this->flutterwaveService->validateCustomer($itemId, $customerId);

        if (isset($validationResponse['status']) && $validationResponse['status'] !== 'success') {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer validation failed',
                'details' => $validationResponse,
            ], 400);
        }

        $response = $this->flutterwaveService->createBillPayment(
            $billerId,
            $itemId,
            $country,
            $customerId,
            $amount,
            $reference,
            $callbackUrl
        );

        return response()->json($response);
    }

    public function getBillerItems(Request $request)
    {
        $billerId = $request->input('biller_id', 'BIL119');
        $response = $this->flutterwaveService->getBillerItems($billerId);
        return response()->json($response);
    }

    public function getBillers(Request $request)
    {
        $category = $request->input('category');
        $country = $request->input('country', 'NG');

        $response = Cache::remember("billers_{$category}_{$country}", 7200, function () use ($category, $country) {
            return $this->flutterwaveService->getBillers($category, $country);
        });

        return response()->json($response);
    }


    public function importFromJson()
    {
        $filePath = public_path('items.json');
        if (!file_exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'File not found'], 404);
        }

        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        if (!isset($data['data']) || !is_array($data['data'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid JSON structure'], 400);
        }

        $inserted = 0;
        foreach ($data['data'] as $item) {
            DB::table('flutterwave_bills_items')->insert([
                'id' => $item['id'] ?? null,
                'biller_code' => $item['biller_code'] ?? null,
                'name' => $item['name'] ?? null,
                'default_commission' => $item['default_commission'] ?? null,
                'date_added' => $item['date_added'] ?? null,
                'country' => $item['country'] ?? null,
                'is_airtime' => $item['is_airtime'] ?? false,
                'biller_name' => $item['biller_name'] ?? null,
                'item_code' => $item['item_code'] ?? null,
                'short_name' => $item['short_name'] ?? null,
                'fee' => $item['fee'] ?? null,
                'commission_on_fee' => $item['commission_on_fee'] ?? false,
                'reg_expression' => $item['reg_expression'] ?? null,
                'label_name' => $item['label_name'] ?? null,
                'amount' => $item['amount'] ?? null,
                'is_resolvable' => $item['is_resolvable'] ?? false,
                'group_name' => $item['group_name'] ?? null,
                'category_name' => $item['category_name'] ?? null,
                'is_data' => $item['is_data'] ?? false,
                'default_commission_on_amount' => $item['default_commission_on_amount'] ?? null,
                'commission_on_fee_or_amount' => $item['commission_on_fee_or_amount'] ?? null,
                'validity_period' => $item['validity_period'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $inserted++;
        }

        return response()->json([
            'status' => 'success',
            'message' => "Successfully imported {$inserted} bill items",
            'inserted' => $inserted
        ]);
    }
}
