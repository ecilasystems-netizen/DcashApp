<?php

declare(strict_types=1);

namespace App\Livewire\App\Wallet\BuyPower;

use App\Jobs\SafeHaven\SafeHavenProcessElectricityPurchaseJob;
use App\Services\SafeHavenApi\SafehavenElectricityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateBuyPower extends Component
{
    public ?string $provider = null;
    public string $meterType = 'PREPAID';
    public string $meterNumber = '';
    public ?string $customerName = null;
    public ?string $customerAddress = null;
    public int $amount = 0;
    public int $userBalance;
    public ?string $pin = null;
    public ?string $pinError = null;
    public bool $showConfirmationModal = false;
    public bool $showSuccessModal = false;
    public bool $processing = false;

    public array $providers = [];


    protected SafehavenElectricityService $electricityService;

    protected $rules = [
        'provider' => 'required|string',
        'meterType' => 'required|in:PREPAID,POSTPAID',
        'meterNumber' => 'required|string|min:10|max:13',
        'amount' => 'required|integer|min:900|max:500000',
        'pin' => 'required|digits:4'
    ];

    protected $messages = [
        'provider.required' => 'Please select an electricity provider',
        'meterType.required' => 'Please select a meter type',
        'meterNumber.required' => 'Please enter your meter number',
        'meterNumber.min' => 'Meter number must be at least 10 digits',
        'amount.required' => 'Please enter an amount',
        'amount.min' => 'Minimum amount is ₦900',
        'amount.max' => 'Maximum amount is ₦500,000',
        'pin.required' => 'Please enter your 4-digit PIN',
        'pin.digits' => 'PIN must be exactly 4 digits',
    ];

    public function boot(SafehavenElectricityService $electricityService): void
    {
        $this->electricityService = $electricityService;

        if (method_exists($electricityService, 'getSupportedProviders')) {
            $this->providers = $electricityService->getSupportedProviders();
        } elseif (method_exists($electricityService, 'providers')) {
            $this->providers = $electricityService->providers();
        } else {
            $this->providers = [];
        }
    }

    public function mount(): void
    {
        $this->userBalance = (int) (auth()->user()->wallet->balance ?? 0);
    }

    public function updatedMeterNumber(): void
    {
        $this->reset(['customerName', 'customerAddress']);
    }

    public function updatedProvider(): void
    {
        $this->reset(['meterNumber', 'customerName', 'customerAddress']);
    }

    public function updatedPin(): void
    {
        $this->pinError = null;
    }

    public function validatePurchase(): void
    {
        $this->validate([
            'provider' => 'required|string',
            'meterType' => 'required|in:PREPAID,POSTPAID',
            'meterNumber' => 'required|string|min:10|max:13',
            'amount' => 'required|integer|min:900|max:500000',
        ]);

        if (!$this->customerName) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'meterNumber' => 'Please verify your meter number first.'
            ]);
        }

        if ($this->userBalance < $this->amount) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount' => 'Insufficient balance. Please fund your wallet.'
            ]);
        }
    }

    public function verifyMeter(): void
    {
        $this->validate([
            'provider' => 'required|string',
            'meterType' => 'required|in:PREPAID,POSTPAID',
            'meterNumber' => 'required|string|min:10|max:13',
        ]);

        try {
            $response = $this->electricityService->verifyMeter(
                $this->meterNumber,
                $this->provider,
            );

            if (in_array($response['status'], [200, 201])) {
                $data = $response['json']['data'] ?? [];

                $this->customerName = $data['name'] ?? $data['customerName'] ?? null;
                $this->customerAddress = $data['address'] ?? null;
                $this->meterType = $data['vendType'] ?? $this->meterType;

                if ($this->customerName) {
                    session()->flash('success', 'Meter verified successfully!');
                } else {

                    session()->flash('error', 'Could not verify meter details. Please try again.');
                }
            } else {

                $errorMessage = $response['json']['message'] ?? 'Could not verify meter number';
                session()->flash('error', $errorMessage);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while verifying meter. Please try again.');
        }
    }

    public function cancelPurchase(): void
    {
        $this->showConfirmationModal = false;
        $this->pin = null;
        $this->pinError = null;
    }

    public function confirmPurchase(): void
    {
        $this->validate();

        $this->processing = true;
        $this->pinError = null;

        try {
            $reference = 'electricity_'.time().'_'.auth()->id();

            if (!Hash::check($this->pin, auth()->user()->pin)) {
                $this->pinError = 'Invalid PIN. Please try again.';
                $this->processing = false;
                return;
            }

            $user = auth()->user();
            $amount = (int) $this->amount;
            $wallet = $user->wallet;
            $balanceBefore = $wallet->balance;

            if ($balanceBefore < $amount) {
                $this->pinError = 'Insufficient balance. Please fund your wallet.';
                $this->processing = false;
                return;
            }

            // Atomic balance deduction
            $updated = DB::table('wallets')
                ->where('id', $wallet->id)
                ->where('balance', '>=', $amount)
                ->decrement('balance', $amount);

            if (!$updated) {
                $this->pinError = 'Insufficient balance. Please try again.';
                $this->processing = false;
                return;
            }

            $balanceAfter = $balanceBefore - $amount;

            // Create a transaction record
            $transaction = $user->wallet->transactions()->create([
                'reference' => $reference,
                'type' => 'electricity',
                'direction' => 'debit',
                'user_id' => $user->id,
                'amount' => $amount,
                'charge' => 0,
                'description' => "Electricity purchase for {$this->meterNumber} ({$this->providers[$this->provider]})",
                'status' => 'pending',
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'metadata' => [
                    'provider' => $this->provider,
                    'provider_name' => $this->providers[$this->provider],
                    'meter_type' => $this->meterType,
                    'meter_number' => $this->meterNumber,
                    'customer_name' => $this->customerName,
                    'customer_address' => $this->customerAddress,
                    'queued_at' => now()->toDateTimeString()
                ],
            ]);

            // Dispatch job to process the purchase
            SafeHavenProcessElectricityPurchaseJob::dispatch(
                $reference,
                $user->id,
                $this->meterNumber,
                $this->provider,
                $this->meterType,
                $amount,
                $this->customerName
            );

            // Set modal states and dispatch to Alpine
            $this->showConfirmationModal = false;
            $this->showSuccessModal = true;

            // Force Alpine to sync
            $this->dispatch('transaction-success');

            session()->flash('success', 'Electricity purchase is being processed!');


        } catch (\Exception $e) {
            Log::error('CreateBuyPower - confirmPurchase exception', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            $this->pinError = 'An error occurred. Please try again.';
        }

        $this->processing = false;
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal = false;
        $this->reset([
            'provider',
            'meterType',
            'meterNumber',
            'customerName',
            'customerAddress',
            'amount',
            'pin',
            'pinError'
        ]);
        $this->meterType = 'PREPAID';

        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.app.wallet.buy-power.create-buy-power')
            ->layout('layouts.app.app')
            ->title('Buy Electricity');
    }
}
