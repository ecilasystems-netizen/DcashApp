<?php

namespace App\Livewire\App\Wallet\Transfers;

use App\Models\SafehavenBank;
use App\Services\SafeHavenApi\TransfersService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreateTransfer extends Component
{
    public $banks = [];
    public $selectedBank = null;
    public $accountNumber = '';
    public $amount = '';
    public $narration = '';
    public $verifiedAccountName = '';
    public $nameEnquiryReference = '';
    public $accountNameStatus = '';
    public $accountVerified = false;
    public $pin = '';
    public $userBalance = 0;
    public $transferFee = 0;
    public $showBankDropdown = false;
    public $showConfirmationModal = false;
    public $showErrorModal = false;
    public $errorMessage = '';
    public $isVerifyingAccount = false;
    public $showSuccessModal = false;

    protected $listeners = ['processTransfer'];
    protected TransfersService $transferService;


    public function mount()
    {
        $this->banks = SafehavenBank::orderBy('name')->get()->toArray();
        $this->userBalance = auth()->user()->wallet->balance ?? 0;
        $this->transferFee = 0.00;
    }

    public function boot(TransfersService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function finishTransaction()
    {
        return redirect()->route('dashboard')->with('success', 'Transfer completed successfully');
    }

    public function updatedAccountNumber($value)
    {
        $this->accountVerified = false;
        $this->verifiedAccountName = '';
        $this->accountNameStatus = '';

        if (strlen($value) === 10 && $this->selectedBank) {
            $this->verifyAccount();
        }
    }

    public function verifyAccount()
    {
        if (!$this->selectedBank || strlen($this->accountNumber) !== 10) {
            return;
        }

        $this->dispatch('verification-started');
        $this->accountNameStatus = 'Verifying...';

        $transferService = app(TransfersService::class);

        try {

            // Step 1: Verify account details
            $accountEnquiry = $transferService->accountNameEnquiry(
                $this->accountNumber,
                $this->selectedBank['code']
            );

            if (in_array($accountEnquiry['status'], [200, 201])) {
                $this->verifiedAccountName = $accountEnquiry['json']['data']['accountName'] ?? null;
                $this->accountNameStatus = $accountEnquiry['json']['data']['accountName'] ?? null;
                $this->nameEnquiryReference = $accountEnquiry['json']['data']['sessionId'] ?? null;
                $this->accountVerified = true;
            } else {
                $this->verifiedAccountName = '';
                $this->accountNameStatus = '';
                $this->accountVerified = false;
                $this->showError('Unable to verify account');
            }

        } catch (\Exception $e) {
            $this->showError('An error occurred while verifying the account.');
        } finally {
            $this->dispatch('verification-ended');
        }
    }

    public function showError($message)
    {
        $this->errorMessage = $message;
        $this->showErrorModal = true;
        $this->showConfirmationModal = false;
    }

    public function updatedSelectedBank()
    {
        $this->accountVerified = false;
        $this->verifiedAccountName = '';
        $this->accountNameStatus = '';

        if (strlen($this->accountNumber) === 10) {
            $this->verifyAccount();
        }
    }

    public function toggleBankDropdown()
    {
        $this->showBankDropdown = !$this->showBankDropdown;
    }

    public function selectBank($bankIndex)
    {
        $this->selectedBank = $this->banks[$bankIndex];
        $this->showBankDropdown = false;
    }

    public function selectAmount($value)
    {
        $this->amount = $value;
    }

    public function openConfirmationModal()
    {
        if ($this->isFormValid()) {
            $this->showConfirmationModal = true;
        }
    }

    public function isFormValid()
    {
        return $this->verifiedAccountName &&
            $this->verifiedAccountName !== 'Verifying...' &&
            (int) $this->amount > 0 &&
            $this->selectedBank !== null;
    }

    public function processTransfer()
    {
        if (strlen($this->pin) !== 4) {
            $this->showError('Please enter your 4-digit PIN');
            return;
        }

        if (!$this->accountVerified) {
            $this->showError('Please verify account details first');
            return;
        }

        if ((int) $this->amount <= 0) {
            $this->showError('Please enter a valid amount');
            return;
        }

        if ($this->amount + $this->transferFee > $this->userBalance) {
            $this->showError('Insufficient balance');
            return;
        }

        $user = auth()->user();

        // verify PIN against user's stored PIN first
        if (!Hash::check($this->pin, $user->pin)) {
            $this->showError('Invalid PIN');
            return;
        }

        $user->refresh();
        if ($user->wallet->balance < ($this->amount + $this->transferFee)) {
            $this->showError('Insufficient balance');
            return;
        }

        //$this->showError('We are currently upgrading the wallet system, please try again later. Thank you');
        //return;

        // Debit wallet
        $user->wallet->balance -= ($this->amount + $this->transferFee);
        $user->wallet->save();

        // Record transaction
        $transaction = $user->wallet->transactions()->create([
            'reference' => 'TRF'.strtoupper(uniqid()).'_'.now()->format('YmdHis'),
            'type' => 'transfer',
            'direction' => 'debit',
            'user_id' => $user->id,
            'amount' => $this->amount,
            'charge' => $this->transferFee,
            'description' => 'Bank transfer to '.$this->verifiedAccountName,
            'status' => 'pending',
            'balance_before' => $this->userBalance,
            'balance_after' => $user->wallet->balance,
            'metadata' => [
                'bank' => $this->selectedBank['name'],
                'bank_code' => $this->selectedBank['code'],
                'account_number' => $this->accountNumber,
                'account_name' => $this->verifiedAccountName,
                'narration' => $this->narration
            ],
        ]);

        //capture the device info and store
        $deviceInfoService = app(\App\Services\DeviceInfoService::class);
        $deviceInfo = $deviceInfoService->getDeviceInfo();
        $transaction->update(['device_info' => json_encode($deviceInfo)]);

        //Initiate transfer
        $transferData = [
            'nameEnquiryReference' => $this->nameEnquiryReference,
            'beneficiaryBankCode' => $this->selectedBank['code'],
            'debitAccountNumber' => config('safehaven.debit_account_number'),
            'beneficiaryAccountNumber' => $this->accountNumber,
            'amount' => (int) $this->amount,
            'narration' => $this->narration,
            'paymentReference' => $transaction->reference,
        ];

        $transferResponse = $this->transferService->initiateTransfer($transferData);

        if (!in_array($transferResponse['status'], [200, 201])) {
            session()->flash('error',
                'Transfer failed: '.($transferResponse['json']['message'] ?? 'Unknown error'));
            return null;
        }

        // Update transaction status
        $transaction->update([
            'status' => 'completed'
        ]);

        // Store transaction metadata
        $metadata = $transaction->metadata ?? [];
        $metadata['transfer_session_id'] = $transferResponse['json']['data']['sessionId'] ?? null;
        $metadata['transfer_status'] = $transferResponse['json']['data']['status'] ?? null;
        $metadata['verified_account_name'] = $this->verifiedAccountName;
        $metadata['transfer_date'] = now()->toDateTimeString();
        $transaction->metadata = $metadata;
        $transaction->save();


        $this->showConfirmationModal = false;
        $this->showSuccessModal = true;
    }

    public function render()
    {
        return view('livewire.app.wallet.transfers.create-transfer')
            ->layout('layouts.app.app')
            ->title('Make Transfer');
    }
}
