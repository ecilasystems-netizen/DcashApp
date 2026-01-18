<?php

namespace App\Livewire\App\Wallet\Airtime;

use App\Jobs\SafeHaven\SafeHavenProcessAirtimePurchaseJob;
use App\Models\SafehavenAirtimeProvider;
use App\Models\WalletTransaction;
use App\Services\FlutterwaveBillsService;
use App\Services\SafeHavenApi\SafehavenBillsPaymentService;
use App\Services\SafeHavenApi\TransfersService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class IndexAirtime extends Component
{
    public $selectedNetwork = null;
    public $phoneNumber = '';
    public $selectedAmount = null;
    public $customAmount = '';
    public $predefinedAmounts = [50, 100, 200, 500, 1000, 2000];
    public $networks = [];
    public $showConfirmModal = false;
    public $pin = '';
    public $showSuccessModal = false;
    public $processing = false;
    public int $userBalance;
    public $pinError = null; // Add this property for PIN validation errors

    protected $rules = [
        'selectedNetwork' => 'required',
        'phoneNumber' => 'required|min:10|max:11',
        'pin' => 'required|digits:4'
    ];
    protected $networkeds = [];

    protected SafehavenBillsPaymentService $billsService;

    public function boot(SafehavenBillsPaymentService $billsService)
    {
        $this->billsService = $billsService;
    }

    public function mount()
    {
        //get the safehaven networks for airtime purchase
        $getNetworks = SafehavenAirtimeProvider::all();

        foreach ($getNetworks as $network) {
            $this->networks[] = [
                'code' => $network->_id,
                'name' => $network->name,
                'short_name' => $network->name,
                'logo' => $network->logoUrl
            ];
        }

        $this->userBalance = auth()->user()->wallet->balance ?? 0;
    }

    public function render()
    {
        return view('livewire.app.wallet.airtime.index-airtime')
            ->layout('layouts.app.app', [
                'title' => 'Airtime Purchase',
            ]);
    }

    public function selectNetwork($networkCode)
    {
        $this->selectedNetwork = $networkCode;
    }

    public function selectAmount($amount)
    {
        $this->selectedAmount = $amount;
        $this->customAmount = $amount;
    }

    public function updatedCustomAmount()
    {
        if ($this->customAmount) {
            $this->selectedAmount = (int) $this->customAmount;
        } else {
            $this->selectedAmount = null;
        }
    }

    public function updatedPin()
    {
        // Clear PIN error when user starts typing
        $this->pinError = null;
    }

    public function openConfirmationModal()
    {
        if (!$this->canProceed()) {
            return;
        }

        $this->showConfirmModal = true;
        $this->pinError = null; // Clear any previous PIN errors
    }

    public function canProceed()
    {
        return $this->selectedNetwork &&
            $this->phoneNumber &&
            strlen($this->phoneNumber) >= 10 &&
            $this->getSelectedAmountProperty() > 0;
    }

    public function getSelectedAmountProperty()
    {
        if ($this->customAmount && is_numeric($this->customAmount)) {
            return (int) $this->customAmount;
        }
        return $this->selectedAmount ?? 0;
    }

    public function cancelPurchase()
    {
        $this->showConfirmModal = false;
        $this->pin = '';
        $this->pinError = null;
    }

    public function confirmPurchase()
    {
        $this->validate();

        $this->processing = true;
        $this->pinError = null; // Clear any previous errors

        try {
            $reference = 'airtime_'.time().'_'.auth()->id();

            // Verify PIN
            if (!Hash::check($this->pin, auth()->user()->pin)) {
                $this->pinError = 'Invalid PIN. Please try again.';
                $this->processing = false;
                return;
            }

            $user = auth()->user();
            $amount = $this->getSelectedAmountProperty();
            $wallet = $user->wallet;
            $balanceBefore = $wallet->balance;

            // Check for sufficient balance
            if ($balanceBefore < $amount) {
                $this->pinError = 'Insufficient balance. Please fund your wallet.';
                $this->processing = false;
                return;
            }

            // Atomically reserve funds
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

            // Create pending transaction
            $transaction = $user->wallet->transactions()->create([
                'reference' => $reference,
                'type' => 'airtime',
                'direction' => 'debit',
                'user_id' => $user->id,
                'amount' => $amount,
                'charge' => 0,
                'description' => 'Airtime to '.$this->phoneNumber,
                'status' => 'pending',
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'metadata' => [
                    'phone_number' => $this->phoneNumber,
                    'network' => $this->selectedNetwork,
                    'queued_at' => now()->toDateTimeString()
                ],
            ]);

            // Dispatch job to queue
            SafeHavenProcessAirtimePurchaseJob::dispatch(
                $reference,
                $user->id,
                $this->phoneNumber,
                $amount,
                $this->selectedNetwork
            )->onQueue('high');

            // Show immediate success feedback
            $this->showConfirmModal = false;
            session()->flash('success',
                'Your airtime purchase is being processed. You will receive a notification shortly.');

            $this->reset(['selectedNetwork', 'phoneNumber', 'selectedAmount', 'customAmount', 'pin', 'pinError']);
            $this->showSuccessModal = true;

        } catch (\Exception $e) {
            Log::error('IndexAirtime - confirmPurchase exception', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            $this->pinError = 'An error occurred. Please try again.';
        }

        $this->processing = false;
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->reset(['selectedNetwork', 'phoneNumber', 'selectedAmount', 'customAmount']);
        //redirect to dashboard route
        return redirect()->route('dashboard');
    }

    private function getItemCodeForNetwork($billerCode)
    {
        $itemCodes = [
            'BIL099' => 'AT099', // MTN
            'BIL102' => 'AT133', // GLO
            'BIL100' => 'AT100', // AIRTEL
            'BIL103' => 'AT134'  // 9MOBILE
        ];

        return $itemCodes[$billerCode] ?? null;
    }
}
