<?php

namespace App\Livewire\App\Wallet\Transfers;

use App\Models\NigerianBank;
use App\Services\FlutterwaveService;
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

    public function mount()
    {
        $this->banks = NigerianBank::all()->toArray();
        $this->userBalance = auth()->user()->wallet->balance ?? 0;
        $this->transferFee = 0.00;
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

        $flutterwaveService = app(FlutterwaveService::class);

        try {
            $result = $flutterwaveService->verifyNigerianBankAccount(
                $this->accountNumber,
                $this->selectedBank['code']
            );

            if ($result['success']) {
                $this->verifiedAccountName = $result['data']['account_name'];
                $this->accountNameStatus = $result['data']['account_name'];
                $this->accountVerified = true;
            } else {
                $this->verifiedAccountName = '';
                $this->accountNameStatus = '';
                $this->accountVerified = false;
                $this->showError($result['message'] ?? 'Unable to verify account');
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

        // Debit wallet
        $user->wallet->balance -= ($this->amount + $this->transferFee);
        $user->wallet->save();

        // Record transaction
        $transaction = $user->wallet->transactions()->create([
            'reference' => 'TRF'.strtoupper(uniqid()),
            'type' => 'transfer',
            'direction' => 'debit',
            'user_id' => $user->id,
            'amount' => $this->amount,
            'fee' => $this->transferFee,
            'description' => 'Bank transfer to '.$this->verifiedAccountName,
            'status' => 'pending',
            'balance_before' => $this->userBalance,
            'balance_after' => $user->wallet->balance,
            'metadata' => [
                'bank' => $this->selectedBank['name'],
                'account_number' => $this->accountNumber,
                'account_name' => $this->verifiedAccountName,
                'narration' => $this->narration
            ],
        ]);

        // Make transfer via Flutterwave
        $flutterwaveService = app(FlutterwaveService::class);
        $transferResult = $flutterwaveService->makeTransfer([
            'account_bank' => $this->selectedBank['code'],
            'account_number' => $this->accountNumber,
            'amount' => $this->amount,
            'narration' => $this->narration,
            'currency' => 'NGN',
            'reference' => $transaction->reference,
        ]);

        \Log::info('Flutterwave transfer response', ['response' => $transferResult]);

        if ($transferResult['success']) {
            $transaction->status = 'completed';
            $transaction->save();
        } else {
            $transaction->status = 'failed';
            $transaction->save();
            $this->showError($transferResult['message'] ?? 'Transfer failed');
            return;
        }
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
