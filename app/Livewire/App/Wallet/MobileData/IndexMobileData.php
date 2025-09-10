<?php

namespace App\Livewire\App\Wallet\MobileData;

use App\Models\WalletTransaction;
use App\Services\FlutterwaveBillsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class IndexMobileData extends Component
{
    public $selectedNetwork = '';
    public $mobileNumber = '';
    public $selectedPlan = '';
    public $dataPlans = [];
    public $networkNames = [
        'BIL108' => 'MTN',
        'BIL109' => 'GLO',
        'BIL110' => 'AIRTEL',
        'BIL111' => '9MOBILE'
    ];
    public $processing = false;
    public $showConfirmModal = false;
    public $pin = '';
    public $showSuccessModal = false;
    public $showErrorModal = false;
    public $currentTab = 'daily';
    public int $userBalance;
    protected $rules = [
        'selectedNetwork' => 'required',
        'mobileNumber' => 'required|digits:11',
        'selectedPlan' => 'required',
        'pin' => 'required|digits:4',
    ];

    public function mount()
    {
        $this->dataPlans = collect();
        $this->userBalance = auth()->user()->wallet->balance ?? 0;

    }

    public function render()
    {
        return view('livewire.app.wallet.mobile-data.index-mobile-data')
            ->layout('layouts.app.app')
            ->title('Buy Mobile Data');
    }

    public function selectNetwork($network)
    {
        $this->selectedNetwork = $network;
        $this->loadDataPlans();
        $this->dispatch('plansLoaded');
    }

    public function loadDataPlans()
    {
        if (empty($this->selectedNetwork)) {
            $this->dataPlans = collect();
            $this->selectedPlan = '';
            return;
        }

        try {
            $flutterwave = new FlutterwaveBillsService();
            $response = $flutterwave->getBillerItems($this->selectedNetwork);

            if (isset($response['data']) && is_array($response['data'])) {
                // Filter for data plans only (is_data = true)
                // Remove duplicate item_codes by using unique() on the collection
                $filteredPlans = collect($response['data'])->filter(function ($plan) {
                    return isset($plan['is_data']) && $plan['is_data'] === true;
                })->unique('item_code');

                // Group plans by validity period
                $this->dataPlans = $filteredPlans->groupBy(function ($plan) {
                    $validity = (int) ($plan['validity_period'] ?? null);
                    if ($validity === 1 or $validity === '1s') {
                        return 'daily';
                    }
                    if ($validity === 7 or $validity === '7s') {
                        return 'weekly';
                    }
                    if ($validity === 30 or $validity === '30s' or $validity === 31 or $validity === '31s') {
                        return 'monthly';
                    }

                    return 'others';
                });
            } else {
                $this->dataPlans = collect();
            }

            $this->selectedPlan = '';
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load data plans: '.$e->getMessage());
            $this->dataPlans = collect();
        }
    }

    public function selectTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function selectPlan($planCode)
    {
        $this->selectedPlan = $planCode;
    }

    public function openConfirmationModal()
    {
        $this->validate([
            'selectedNetwork' => 'required',
            'mobileNumber' => 'required|digits:11',
            'selectedPlan' => 'required',
        ]);

        $this->pin = '';
        $this->showConfirmModal = true;
    }

    public function cancelPurchase()
    {
        $this->showConfirmModal = false;
    }

    public function confirmPurchase()
    {
        $this->validate();

        $this->processing = true;

        try {
            // Get all plans from all categories
            $allPlans = collect();
            foreach ($this->dataPlans as $categoryPlans) {
                $allPlans = $allPlans->merge($categoryPlans);
            }

            // Find the selected plan
            $planDetails = $allPlans->firstWhere('item_code', $this->selectedPlan);

            if (!$planDetails) {
                session()->flash('error', 'Selected plan not found');
                $this->showConfirmModal = false;
                return;
            }

            // Process payment using Flutterwave
            $flutterwave = new FlutterwaveBillsService();
            $reference = 'data_'.time().'_'.auth()->id();

            $user = auth()->user();

            // verify PIN against user's stored PIN first
            if (!Hash::check($this->pin, $user->pin)) {
                session()->flash('error', 'Invalid PIN');
                $this->processing = false;
                return;
            }

            //validate customer details
            $validationResponse = $flutterwave->validateCustomer($planDetails['item_code'], $this->mobileNumber);
            if (isset($validationResponse['status']) && $validationResponse['status'] !== 'success') {
                session()->flash('error', 'Customer validation failed: '.$validationResponse['message']);
                $this->processing = false;
                $this->showConfirmModal = false;
                return;
            }

            // Atomically reserve funds and create a pending transaction
            $amount = $planDetails['amount'];
            $wallet = $user->wallet;
            $balanceBefore = $wallet->balance;

            // Attempt atomic decrement to avoid race conditions
            $updated = \DB::table('wallets')
                ->where('id', $wallet->id)
                ->where('balance', '>=', $amount)
                ->decrement('balance', $amount);

            if (!$updated) {
                session()->flash('error', 'Insufficient balance');
                $this->showErrorModal = true;
                $this->processing = false;
                $this->showConfirmModal = false;
                return;
            }

            $balanceAfter = $balanceBefore - $amount;
            $user->refresh(); // ensure relations reflect updated balance

            // Record transaction
            $user->wallet->transactions()->create([
                'reference' => $reference,
                'type' => 'data',
                'direction' => 'debit',
                'user_id' => $user->id,
                'amount' => $amount,
                'charge' => 0,
                'description' => 'Data purchase to '.$this->mobileNumber,
                'status' => 'pending',
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'metadata' => [
                    'phone_number' => $this->mobileNumber,
                    'network' => $this->networkNames[$this->selectedNetwork] ?? $this->selectedNetwork,
                    'plan' => $planDetails['name'] ?? 'N/A',
                    'item_code' => $planDetails['item_code'] ?? 'N/A',
                    'biller_code' => $planDetails['biller_code'] ?? 'N/A'
                ],
            ]);

            // Create bill payment
            $response = $flutterwave->createBillPayment(
                $planDetails['biller_code'],
                $planDetails['item_code'],
                'NG', // Country code for Nigeria
                $this->mobileNumber,
                $planDetails['amount'],
                $reference,
                $planDetails['name'],
                true // isDataPurchase
            );

            if (isset($response['status']) && $response['status'] === 'success') {

                //log the response
                Log::info('Data purchase successful:', ['response' => $response]);

                // Update transaction status to completed
                $transaction = $user->wallet->transactions()->where('reference', $reference)->first();
                if ($transaction) {
                    $transaction->status = 'completed';
                    $transaction->metadata = array_merge((array) $transaction->metadata, [
                        'tx_ref' => $response['data']['tx_ref'] ?? null,
                        'flw_ref' => $response['data']['reference'] ?? null,
                    ]);
                    $transaction->save();
                }

                $this->showConfirmModal = false;
                $this->showSuccessModal = true;

            } else {
                // On failed payment (after createBillPayment returns non-success) — refund and mark transaction failed
                // get the pending transaction
                $transaction = $user->wallet->transactions()->where('reference', $reference)->first();
                if ($transaction) {
                    $transaction->status = 'failed';
                    $transaction->metadata = array_merge((array) $transaction->metadata, [
                        'failure_reason' => $response['message'] ?? 'Payment failed',
                    ]);
                    $transaction->save();
                }
                // refund wallet
                \DB::table('wallets')->where('id', $wallet->id)->increment('balance', $amount);

                $user->refresh();

                // Handle failed transaction - show error modal
                $this->showConfirmModal = false;
                $this->showErrorModal = true;
            }

        } catch (\Exception $e) {

            // attempt to find and mark pending transaction as failed and refund
            $transaction = WalletTransaction::where('reference', $reference ?? null)->first();
            if ($transaction && $transaction->status === 'pending') {
                $transaction->status = 'failed';
                $transaction->metadata = array_merge((array) $transaction->metadata, [
                    'exception' => $e->getMessage(),
                ]);
                $transaction->save();

                \DB::table('wallets')->where('id', $wallet->id)->increment('balance', $transaction->amount);
            }

            // Handle exceptions - show error modal
            $this->showConfirmModal = false;
            $this->showErrorModal = true;

            // Log the exception
            Log::error('Exception during data purchase: '.$e->getMessage(), [
                'exception' => $e,
                'user' => auth()->id()
            ]);
        }
        $this->processing = false;
        $this->pin = '';
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->reset(['pin']);
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->reset(['selectedNetwork', 'mobileNumber', 'selectedPlan', 'dataPlans']);
        //redirect to dashboard route
        return redirect()->route('dashboard');
    }

    public function getPlanDetails()
    {
        if (empty($this->selectedPlan)) {
            return null;
        }

        // Get all plans from all categories
        $allPlans = collect();
        foreach ($this->dataPlans as $categoryPlans) {
            $allPlans = $allPlans->merge($categoryPlans);
        }

        return $allPlans->firstWhere('item_code', $this->selectedPlan);
    }
}
