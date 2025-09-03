<?php

namespace App\Services;

use App\Models\User;
use App\Models\VirtualBankAccount;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VirtualAccountService
{
    protected string $provider;
    protected ?string $paystackSecret;
    protected ?string $flutterwaveSecret;
    protected string $paystackUrl = 'https://api.paystack.co';
    protected string $flutterwaveUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->provider = config('services.virtual_account.provider');
        $this->paystackSecret = config('services.paystack.secret');
        $this->flutterwaveSecret = config('services.flutterwave.secret');
    }

    /**
     * Generate and save a new virtual account for a user.
     *
     * @param  User  $user
     * @param  array  $kycData
     * @return VirtualBankAccount|null
     * @throws Exception
     */
    public function generateAccount(User $user, array $kycData): ?VirtualBankAccount
    {
        $accountData = match ($this->provider) {
            'paystack' => $this->generateWithPaystack($user, $kycData),
            'flutterwave' => $this->generateWithFlutterwave($user, $kycData),
            default => throw new Exception("Virtual account provider '{$this->provider}' is not supported."),
        };

        if (!$accountData) {
            return null;
        }

        return VirtualBankAccount::create($accountData);
    }

    /**
     * Generate a virtual account using Paystack.
     *
     * @param  User  $user
     * @param  array  $kycData
     * @return array|null
     * @throws Exception
     */
    private function generateWithPaystack(User $user, array $kycData): ?array
    {
        if (!$this->paystackSecret) {
            throw new Exception('Paystack secret key is not configured.');
        }

        try {
            $trx_ref = 'VA-'.Str::uuid();

            $customerResponse = Http::withToken($this->paystackSecret)
                ->post("{$this->paystackUrl}/customer", [
                    'email' => $user->email,
                    'first_name' => $user->fname,
                    'last_name' => $user->lname,
                    'phone' => $user->phone,
                ]);

            if (!$customerResponse->successful() || !$customerResponse->json('status')) {
                Log::error('Paystack customer creation failed.', $customerResponse->json());
                return null;
            }

            $customerCode = $customerResponse->json('data.customer_code');

            $accountResponse = Http::withToken($this->paystackSecret)
                ->post("{$this->paystackUrl}/dedicated_account", [
                    'customer' => $customerCode,
                    'preferred_bank' => 'wema-bank',
                    'bvn' => $kycData['bvn'] ?? null,
                    'first_name' => $user->fname,
                    'last_name' => $user->lname,
                ]);

            if (!$accountResponse->successful() || !$accountResponse->json('status')) {
                Log::error('Paystack dedicated account creation failed.', $accountResponse->json());
                return null;
            }

            $data = $accountResponse->json('data');

            return [
                'user_id' => $user->id,
                'account_number' => $data['account_number'],
                'account_name' => $data['account_name'],
                'bank_name' => $data['bank']['name'],
                'bank_code' => $data['bank']['id'],
                'provider' => 'paystack',
                'trx_ref' => $trx_ref ?? null,
                'order_ref' => $data['order_ref'] ?? null,
                'is_active' => $data['active'],
            ];
        } catch (Exception $e) {
            Log::error('Error generating Paystack virtual account: '.$e->getMessage());
            return null;
        }
    }

    /**
     * Generate a virtual account using Flutterwave.
     *
     * @param  User  $user
     * @param  array  $kycData
     * @return array|null
     * @throws Exception
     */
    private function generateWithFlutterwave(User $user, array $kycData): ?array
    {
        if (!$this->flutterwaveSecret) {
            throw new Exception('Flutterwave secret key is not configured.');
        }

        try {
            $trx_ref = 'VA-'.Str::uuid();

            $response = Http::withToken($this->flutterwaveSecret)
                ->post("{$this->flutterwaveUrl}/virtual-account-numbers", [
                    'email' => $user->email,
                    'currency' => 'NGN',
                    'bvn' => $kycData['bvn'],
                    'tx_ref' => $trx_ref,
                    'firstname' => $user->fname,
                    'lastname' => $user->lname,
                    'phonenumber' => $user->phone,
                    'is_permanent' => true,
                    'narration' => $user->fname.' '.$user->lname,
                ]);

            if (!$response->successful() || $response->json('status') !== 'success') {
                Log::error('Flutterwave virtual account creation failed.', $response->json());
                return null;
            }

            $data = $response->json('data');

            return [
                'user_id' => $user->id,
                'account_number' => $data['account_number'],
                'account_name' => $user->fname.' '.$user->lname,
                'bank_name' => $data['bank_name'],
                'bank_code' => null, // Flutterwave API does not provide bank code in this response
                'provider' => 'flutterwave',
                'trx_ref' => $trx_ref,
                'order_ref' => $data['order_ref'],
                'is_active' => true,
            ];
        } catch (Exception $e) {
            Log::error('Error generating Flutterwave virtual account: '.$e->getMessage());
            return null;
        }
    }
}
