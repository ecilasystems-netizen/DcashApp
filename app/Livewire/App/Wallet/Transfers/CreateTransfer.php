<?php

namespace App\Livewire\App\Wallet\Transfers;

use App\Models\SafehavenBank;
use App\Services\SafeHavenApi\TransfersService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
    public int $bankListLimit = 50; // Initial load limit

    protected $listeners = ['processTransfer'];
    protected TransfersService $transferService;

    public string $bankSearch = '';

    public function getFilteredBanksProperty(): array
    {
        $banks = collect($this->banks);

        if (!empty($this->bankSearch)) {
            $banks = $banks->filter(fn($bank) =>
            str_contains(
                strtolower($bank['name']),
                strtolower($this->bankSearch)
            )
            );
        }

        // Return limited results for faster rendering
        return $banks->take($this->bankListLimit)->values()->toArray();
    }

    public function selectBank(string $bankCode): void
    {
        $this->selectedBank = collect($this->banks)
            ->firstWhere('code', $bankCode);

        $this->showBankDropdown = false;
        $this->bankSearch = ''; // Reset search when bank is selected
    }

    public function loadMoreBanks(): void
    {
        $this->bankListLimit += 50;
    }

    public function updatedBankSearch(): void
    {
        // Reset limit when searching
        $this->bankListLimit = 50;
    }

    public function toggleBankDropdown(): void
    {
        $this->showBankDropdown = !$this->showBankDropdown;

        if ($this->showBankDropdown) {
            $this->bankSearch = '';
            $this->bankListLimit = 50;
        }
    }

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

//    public function toggleBankDropdown()
//    {
//        $this->showBankDropdown = !$this->showBankDropdown;
//    }

//    public function selectBank($bankIndex)
//    {
//        $this->selectedBank = $this->banks[$bankIndex];
//        $this->showBankDropdown = false;
//    }

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

        // Verify PIN against user's stored PIN first
        if (!Hash::check($this->pin, $user->pin)) {
            $this->showError('Invalid PIN');
            return;
        }

        try {
            DB::transaction(function () use ($user) {
                $user->refresh();
                $wallet = $user->wallet;
                $totalDebit = $this->amount + $this->transferFee;

                // Atomically reserve funds with optimistic locking
                $updated = DB::table('wallets')
                    ->where('id', $wallet->id)
                    ->where('balance', '>=', $totalDebit)
                    ->decrement('balance', $totalDebit);

                if (!$updated) {
                    throw new \Exception('Insufficient balance');
                }

                $balanceBefore = $this->userBalance;
                $balanceAfter = $balanceBefore - $totalDebit;

                // Create pending transaction
                $transaction = $user->wallet->transactions()->create([
                    'reference' => 'TRF' . strtoupper(uniqid()) . '_' . now()->format('YmdHis'),
                    'type' => 'transfer',
                    'direction' => 'debit',
                    'user_id' => $user->id,
                    'amount' => $this->amount,
                    'charge' => $this->transferFee,
                    'description' => 'Bank transfer to ' . $this->verifiedAccountName,
                    'status' => 'pending',
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'metadata' => [
                        'bank' => $this->selectedBank['name'],
                        'bank_code' => $this->selectedBank['code'],
                        'account_number' => $this->accountNumber,
                        'account_name' => $this->verifiedAccountName,
                        'narration' => $this->narration,
                        'queued_at' => now()->toDateTimeString()
                    ],
                ]);

                // Capture device info
                $deviceInfoService = app(\App\Services\DeviceInfoService::class);
                $deviceInfo = $deviceInfoService->getDeviceInfo();
                $transaction->update(['device_info' => json_encode($deviceInfo)]);

                // Dispatch job to queue
                \App\Jobs\SafeHaven\SafeHavenProcessBankTransferJob::dispatch(
                    $transaction->reference,
                    $user->id,
                    $this->nameEnquiryReference,
                    $this->selectedBank['code'],
                    $this->accountNumber,
                    (int) $this->amount,
                    $this->narration
                )->onQueue('high');
            });

            // Show immediate success feedback
//            $this->showConfirmationModal = false;
            session()->flash('success', 'Your transfer is being processed. You will receive a notification shortly.');

            // Reset form
            $this->reset(['selectedBank', 'accountNumber', 'amount', 'narration', 'verifiedAccountName', 'pin']);


            $this->showConfirmationModal = false;
            $this->showSuccessModal = true;

        } catch (\Exception $e) {
            Log::error('CreateTransfer - processTransfer exception', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            $this->showError($e->getMessage() === 'Insufficient balance'
                ? 'Insufficient balance'
                : 'An error occurred. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.app.wallet.transfers.create-transfer')
            ->layout('layouts.app.app')
            ->title('Make Transfer');
    }
}
