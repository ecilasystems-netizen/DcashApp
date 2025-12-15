<?php

namespace App\Http\Controllers;

use App\Services\SafeHavenApi\AccountsService;
use App\Services\SafeHavenApi\TransfersService;
use App\Services\SafeHavenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    // common API variables extracted to class properties
    protected string $clientId = '68de621ccd23d700243524ba';
    protected string $bearerToken = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2FwaS5zYWZlaGF2ZW5tZmIuY29tIiwic3ViIjoiYWJlODFkMTU1ODEzZWU5MGUwNGM4NDhjMmQxYmMyYTciLCJhdWQiOiJodHRwczovL2RjYXNod2FsbGV0LmNvbSIsImp0aSI6ImM4OWUzMDNlMzNjN2MyMTliYWZkMmE2MGNlNDViYjY0IiwiZ3JhbnRfdHlwZSI6ImFjY2Vzc190b2tlbiIsInNjb3BlcyI6WyJSRUFEIiwiV1JJVEUiLCJQQVkiXSwiaWJzX2NsaWVudF9pZCI6IjY4ZGU2MjFjY2QyM2Q3MDAyNDM1MjRiYSIsImlic191c2VyX2lkIjoiNjhkYzZjMmNhNjYwYjkwMDI0ZTNhOTkxIiwiaWF0IjoxNzY0NTY0NDEyLCJleHAiOjE3NjQ1NjY4MTJ9.eCV6fHzpPVqHgN0uBR4I8dG07Q6yhTD8y27GFFSuEYMOLUUrc8hfbfAOxl913Q2838K4oQmdNT3J9QpG4Qs1ZVgNDBb9lNaUn-zYMFV7su0zZMBPMYcRfMsLgO393vCiJ9xt5YwDEyzBaBLCaz2L0Wp4D0t_ZwZJUk9E6HaoD3Y';
    protected string $baseApi = 'https://api.safehavenmfb.com';
    protected array $headers;

    public function __construct()
    {
        $this->headers = [
            "ClientID: {$this->clientId}",
            "Authorization: Bearer {$this->bearerToken}",
            "accept: application/json",
            "content-type: application/json",
        ];
    }

    // helper to send POST requests using curl
    protected function sendPost(string $endpoint, array $payload): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseApi.$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => $this->headers,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return ['response' => $response, 'error' => $error, 'status' => $status];
    }

    public function initiateBvnVerification(Request $request)
    {
        $bvn = $request->input('bvn');
        $payload = [
            'type' => 'BVN',
            'async' => false,
            'debitAccountNumber' => '0119358126',
            'number' => $bvn,
        ];

        $result = $this->sendPost('/identity/v2', $payload);

        if (!empty($result['error'])) {
            Log::error('BVN Verification Error: '.$result['error']);
            return response()->json(['error' => 'Verification failed.'], 500);
        }

        $data = json_decode($result['response'], true);
        Log::info('BVN Verification Response', $data ?? []);

        if (empty($data['data']['reference'])) {
            return response()->json(['error' => 'Verification unsuccessful.'], 400);
        }

        return response()->json(['reference' => $data['data']['reference']]);
    }

    public function validateBvnOtp(Request $request)
    {
        $reference = $request->input('reference');
        $otp = $request->input('otp');
        $payload = [
            'reference' => $reference,
            'otp' => $otp,
        ];

        $result = $this->sendPost('/identity/v2/validate', $payload);

        if (!empty($result['error'])) {
            Log::error('BVN OTP Validation Error: '.$result['error']);
            return response()->json(['error' => 'OTP validation failed.'], 500);
        }

        $data = json_decode($result['response'], true);
        Log::info('BVN OTP Validation Response', $data ?? []);

        if (empty($data['data']['bvn'])) {
            return response()->json(['error' => 'OTP validation unsuccessful.'], 400);
        }

        return response()->json(['bvn_data' => $data['data']]);
    }

    public function createSafeHavenSubAccount(Request $request)
    {
        $bvn = $request->input('bvn');
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $email = $request->input('email');
        $phone = $request->input('phone');

        $payload = [
            'bvn' => $bvn,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phone' => $phone,
        ];

        $result = $this->sendPost('/account/v2/sub', $payload);

        if (!empty($result['error'])) {
            Log::error('Sub Account Creation Error: '.$result['error']);
            return response()->json(['error' => 'Sub account creation failed.'], 500);
        }

        $data = json_decode($result['response'], true);
        Log::info('Sub Account Creation Response', $data ?? []);

        return response()->json(['sub_account' => $data]);
    }

    public function getSafeHavenBankList()
    {
        try {
            // Get all banks
            $safeHaven = new SafeHavenService();
            $response = $safeHaven->getBankList();

            if ($response['status'] === 200 && isset($response['json']['data'])) {
                $banks = $response['json']['data'];

                return response()->json(['banks' => $banks]);
            }

            return response()->json(['error' => 'Failed to fetch bank list.'], 502);
        } catch (\Exception $e) {
            Log::error('Error fetching bank list: '.$e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function syncSafeHavenBankList()
    {
        $safeHaven = new SafeHavenService();
        $result = $safeHaven->syncBanksToDatabase();

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'synced' => $result['synced']
            ]);
        }

        return response()->json([
            'error' => $result['message']
        ], 500);
    }

    public function verifyAccountDetails(Request $request)
    {
        $accountNumber = $request->input('accountNumber');
        $bankCode = $request->input('bankCode');

        if (!$accountNumber || !$bankCode) {
            return response()->json([
                'error' => 'Account number and bank code are required'
            ], 400);
        }

        try {
            $safeHaven = new SafeHavenService();
            $response = $safeHaven->accountNameEnquiry($accountNumber, $bankCode);

            if (in_array($response['status'], [200, 201]) && isset($response['json']['data'])) {
                return response()->json([
                    'success' => true,
                    'data' => $response['json']['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['json']['message'] ?? 'Account verification failed'
            ], $response['status']);

        } catch (\Exception $e) {
            Log::error('Error verifying account: '.$e->getMessage());
            return response()->json([
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function initiateTransfer(Request $request)
    {
        $validated = $request->validate([
            'nameEnquiryReference' => 'required|string',
            'debitAccountNumber' => 'required|string',
            'amount' => 'required|numeric|min:100',
            'narration' => 'required|string|max:255',
            'paymentReference' => 'nullable|string',
            'saveBeneficiary' => 'nullable|boolean',
            'beneficiaryName' => 'nullable|string',
        ]);

        try {
            $safeHaven = new SafeHavenService();
            $response = $safeHaven->initiateTransfer($validated);

            if (in_array($response['status'], [200, 201]) && isset($response['json']['data'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transfer initiated successfully',
                    'data' => $response['json']['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['json']['message'] ?? 'Transfer failed'
            ], $response['status']);

        } catch (\Exception $e) {
            Log::error('Error initiating transfer: '.$e->getMessage());
            return response()->json([
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function getTransferStatus(Request $request, string $sessionId)
    {
        try {
            $safeHaven = new SafeHavenService();
            $response = $safeHaven->getTransferStatus($sessionId);

            if (in_array($response['status'], [200, 201]) && isset($response['json']['data'])) {
                return response()->json([
                    'success' => true,
                    'data' => $response['json']['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['json']['message'] ?? 'Failed to fetch transfer status'
            ], $response['status']);

        } catch (\Exception $e) {
            Log::error('Error fetching transfer status: '.$e->getMessage());
            return response()->json([
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function getBanks(AccountsService $safeHaven)
    {
        $response = $safeHaven->initiateBvnVerification(100004);
        return response()->json([$response]);
    }

    public function safeHaveApiTestRoute(TransfersService $transfersService)
    {
        //verify bank account details

//        $response = $transfersService->accountNameEnquiry('8065686973', '100004');
//        return response()->json([$response]);


        //initiate transfer
        $response = $transfersService->initiateTransfer([
            'nameEnquiryReference' => '090286251213170428593027191339',
            'debitAccountNumber' => '0119358126',
            'beneficiaryBankCode' => '100004',
            'beneficiaryAccountNumber' => '8065686973',
            'amount' => 100.00,
            'narration' => 'Test Transfer',
            'paymentReference' => 'payment-ref-123458',
            'saveBeneficiary' => false,
        ]);


        return response()->json([$response]);

    }
}
