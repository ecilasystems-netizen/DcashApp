<?php

namespace App\Livewire\App\Wallet\Deposits;

use App\Mail\AdminNotificationMail;
use App\Models\AccountLimitUpgradeRequest;
use App\Models\AccountTier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CreateDeposit extends Component
{
    public $accountNumber;
    public $accountName;
    public $bankName;
    public $walletProvider;

    public $showLimitIncreaseModal = false;
    public $occupation;
    public $sourceOfIncome;
    public $currentTier;
    public $showSuccessModal = false;
    public $hasExistingRequest = false;

    public function submitLimitIncrease()
    {
        $this->validate([
            'occupation' => 'required|string|max:100',
            'sourceOfIncome' => 'required|string'
        ]);

        // Add loading state
        $this->dispatch('loading-start');

        try {
            AccountLimitUpgradeRequest::create([
                'user_id' => auth()->id(),
                'occupation' => $this->occupation,
                'source_of_income' => $this->sourceOfIncome,
                'status' => 'submitted'
            ]);

            $this->showLimitIncreaseModal = false;
            $this->showSuccessModal = true;
            $this->hasExistingRequest = true;

            // Send email notification
            Mail::to('limits@dcashwallet.com')->send(new AdminNotificationMail(
                'limit_upgrade',
                Auth::user()->fname,
                Auth::user()->email,
                [
                    'occupation' => $this->occupation,
                    'source_of_income' => $this->sourceOfIncome,
                ],
                route('admin.limit-requests')
            ));

            $this->reset(['occupation', 'sourceOfIncome']);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit request. Please try again.');
            \Log::error('Limit upgrade submission failed: '.$e->getMessage());
        } finally {
            $this->dispatch('loading-end');
        }
    }


    public function mount()
    {
        $virtualAccount = auth()->user()->virtualBankAccount()->where('is_active', true)->first();
        $this->accountNumber = $virtualAccount->account_number ?? '0000000000';
        $this->accountName = $virtualAccount->account_name ?? 'John Doe';
        $this->bankName = $virtualAccount->bank_name ?? 'Example Bank';
        $this->currentTier = AccountTier::find(auth()->user()->wallet()->first()->tier);
        $this->walletProvider = $virtualAccount->provider ?? 'flutterwave';

        // Check for existing requests
        $this->hasExistingRequest = AccountLimitUpgradeRequest::where('user_id', auth()->id())
            ->whereIn('status', ['submitted', 'under_review'])
            ->exists();
    }

    public function render()
    {
        return view('livewire.app.wallet.deposits.create-deposit')->layout('layouts.app.app')->title('Make a Deposit');
    }
}
