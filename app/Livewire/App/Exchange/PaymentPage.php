<?php

namespace App\Livewire\App\Exchange;

use App\Models\CompanyBankAccount;
use App\Models\Currency;
use App\Models\ExchangeTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class PaymentPage extends Component
{
    use WithFileUploads;

    public $companyBankAccountId;
    public $paymentSlips = [];
    public $newPaymentSlip;
    public $exchangeData = [];
    public $expirationTime;
    public $baseCurrencyType;

    public function mount()
    {
        $this->exchangeData = session('exchangeData', []);

        // Check if exchangeData is empty and redirect if necessary
        if (empty($this->exchangeData)) {
            return redirect()->route('dashboard')->with('error',
                'No exchange data found. Please start a new exchange transaction.');
        }

        //get the base currency type from database using the base currency ID
        $this->baseCurrencyId = $this->exchangeData['baseCurrencyId'] ?? null;
        if (!$this->baseCurrencyId) {
            return redirect()->route('dashboard')->with('error',
                'Base currency not set. Please start a new exchange transaction.');
        }
        $this->baseCurrencyType = Currency::where('id', $this->baseCurrencyId)
            ->value('type') ?? 'fiat';

        // Initialize or get the expiration time
        if (!session()->has('exchange_expiration_time')) {
            $expirationTime = now()->addMinutes(15);
            session(['exchange_expiration_time' => $expirationTime]);
        }

        $this->expirationTime = session('exchange_expiration_time');

        // Force redirect if expired on server side
        if (now()->greaterThan($this->expirationTime)) {
            session()->forget(['exchangeData', 'exchange_expiration_time']);
            return redirect()->route('dashboard')->with('error', 'Exchange session has expired');
        }
    }

    public function updatedNewPaymentSlip()
    {
        $this->validate([
            'newPaymentSlip' => 'image|max:5120|mimes:jpg,jpeg,png'
        ]);

        if ($this->newPaymentSlip) {
            $this->paymentSlips[] = $this->newPaymentSlip;
            $this->reset('newPaymentSlip');
        }
    }

    public function removeFile($index)
    {
        if (isset($this->paymentSlips[$index])) {
            unset($this->paymentSlips[$index]);
            $this->paymentSlips = array_values($this->paymentSlips);
        }
    }

    public function hydrate()
    {
        // Rehydrate the expirationTime after Livewire updates
        $this->expirationTime = session('exchange_expiration_time');
    }

    public function cancelTransaction()
    {
        // Clear session data and redirect to dashboard
        session()->forget(['exchangeData', 'exchange_expiration_time']);

        // Ensure a clean redirect without prompts
        session()->flash('suppressBrowserPrompt', true);
        return redirect()->route('dashboard')->with('info', 'Exchange transaction has been cancelled');
    }


    public function saveTransaction()
    {
        $this->validate([
            'paymentSlips' => 'required|array|min:1',
            'paymentSlips.*' => 'image|max:5120|mimes:jpg,jpeg,png',
        ]);

        // Store all payment slip paths as an array
        $paymentProofPaths = [];
        foreach ($this->paymentSlips as $slip) {
            $paymentProofPaths[] = $slip->store('payment_proofs', 'public');
        }

        $data = $this->exchangeData;

        $transaction = ExchangeTransaction::create([
            'reference' => uniqid('EXCH-'),
            'company_bank_account_id' => $this->companyBankAccountId,
            'user_id' => Auth::id(),
            'from_currency_id' => $data['baseCurrencyId'] ?? null,
            'to_currency_id' => $data['quoteCurrencyId'] ?? null,
            'amount_from' => $data['baseAmount'] ?? 0,
            'amount_to' => $data['quoteAmount'] ?? 0,
            'rate' => $data['exchangeRate'] ?? 0,
            'recipient_bank_name' => $data['bank'] ?? null,
            'recipient_account_number' => $data['accountNumber'] ?? null,
            'recipient_account_name' => $data['accountName'] ?? null,
            'recipient_wallet_address' => null,
            'recipient_network' => null,
            'payment_transaction_hash' => null,
            'payment_proof' => json_encode($paymentProofPaths),
            'status' => 'pending_confirmation',
        ]);

        // Optionally clear session or redirect
        session()->forget(['exchangeData', 'exchange_expiration_time']);
        return redirect()->route('exchange.completed', ['ref' => $transaction->reference]);
    }

    public function render()
    {
        $companyBankAccount = CompanyBankAccount::where('currency_id', $this->exchangeData['baseCurrencyId'])
            ->where('is_active', true)
            ->first();

        $this->companyBankAccountId = $companyBankAccount->id;

        return view('livewire.app.exchange.payment-page', [
            'companyBankAccount' => $companyBankAccount,
            'deadline' => $this->expirationTime->getPreciseTimestamp(3)
        ])->layout('layouts.app.app')->title('Exchange Payment');
    }
}
