<?php

namespace App\Livewire\App\Exchange;

use App\Mail\AdminNotificationMail;
use App\Models\CompanyBankAccount;
use App\Models\Currency;
use App\Models\ExchangeTransaction;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\ExchangeTransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

    //
    public $paymentMethod = 'bank_transfer'; // 'bank_transfer' or 'wallet'
    public $userWallet;
    public $walletBalance = 0;
    public $hasInsufficientFunds = false;


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

        // Check if user has an active NGN wallet
        if ($this->exchangeData['baseCurrencyCode'] === 'NGN') {
            $this->userWallet = Wallet::where('user_id', Auth::id())
                ->where('status', 1)
                ->first();

            if ($this->userWallet) {
                $this->walletBalance = $this->userWallet->balance;
                $requiredAmount = $this->exchangeData['baseAmount'] - ($this->exchangeData['baseAmount'] * 0.001);
                $this->hasInsufficientFunds = $this->walletBalance < $requiredAmount;
            }
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

    public function updatedPaymentMethod()
    {
        if ($this->paymentMethod === 'wallet') {
            $this->paymentSlips = [];
            $this->newPaymentSlip = null;
        }
    }

    public function saveTransaction()
    {

        if ($this->paymentMethod === 'wallet') {
            $this->processWalletPayment();
            return null;
        }

        $this->validate([
            'paymentSlips' => 'required|array|min:1',
            'paymentSlips.*' => 'image|max:5120|mimes:jpg,jpeg,png',
        ]);

        $exchangeService = new ExchangeTransactionService();

        $transaction = $exchangeService->createTransaction(
            $this->exchangeData,
            $this->paymentSlips,
            $this->companyBankAccountId
        );

        //i want to capture the device info and store as json in the field 'device_info' of the exchange transaction table
        $deviceInfoService = app(\App\Services\DeviceInfoService::class);
        $deviceInfo = $deviceInfoService->getDeviceInfo();
        $transaction->update(['device_info' => json_encode($deviceInfo)]);

        // Optionally clear session or redirect
        session()->forget(['exchangeData', 'exchange_expiration_time']);
        return redirect()->route('exchange.completed', ['ref' => $transaction->reference]);
    }


    private function processWalletPayment()
    {
        if (!$this->userWallet) {
            session()->flash('error', 'No active NGN wallet found.');
            return;
        }

        $requiredAmount = $this->exchangeData['baseAmount'] - ($this->exchangeData['baseAmount'] * 0.001);

        if ($this->walletBalance < $requiredAmount) {
            session()->flash('error', 'Insufficient wallet balance.');
            return;
        }

        try {
            DB::transaction(function () use ($requiredAmount) {
                // Create exchange transaction
                $transaction = ExchangeTransaction::create([
                    'reference' => uniqid('EXCH-'),
                    'company_bank_account_id' => $this->companyBankAccountId, // No bank account for wallet payments
                    'user_id' => Auth::id(),
                    'from_currency_id' => $this->exchangeData['baseCurrencyId'] ?? null,
                    'to_currency_id' => $this->exchangeData['quoteCurrencyId'] ?? null,
                    'amount_from' => $this->exchangeData['baseAmount'] ?? 0,
                    'amount_to' => $this->exchangeData['quoteAmount'] ?? 0,
                    'rate' => $this->exchangeData['exchangeRate'] ?? 0,
                    'recipient_bank_name' => $this->exchangeData['bank'] ?? null,
                    'recipient_account_number' => $this->exchangeData['accountNumber'] ?? null,
                    'recipient_account_name' => $this->exchangeData['accountName'] ?? null,
                    'recipient_wallet_address' => $this->exchangeData['walletAddress'] ?? null,
                    'recipient_network' => $this->exchangeData['network'] ?? null,
                    'payment_transaction_hash' => null,
                    'payment_proof' => json_encode(['wallet-payment-default.png']),
                    'note' => ['payment_bank' => 'DCASH Wallet'],
                    'status' => 'pending_confirmation', // Auto-approve wallet payments
                    'cashback' => $this->exchangeData['baseAmount'] * 0.001
                ]);


                // Deduct from wallet
                $this->userWallet->update([
                    'balance' => $this->userWallet->balance - $requiredAmount
                ]);

                // Create wallet transaction record
                WalletTransaction::create([
                    'reference' => 'EXCH-'.$transaction->reference,
                    'wallet_id' => $this->userWallet->id,
                    'user_id' => Auth::id(),
                    'direction' => 'debit',
                    'type' => 'exchange_out',
                    'amount' => $requiredAmount,
                    'charge' => 0,
                    'description' => 'Exchange transaction payment',
                    'status' => 'completed',
                    'balance_before' => $this->userWallet->balance + $requiredAmount,
                    'balance_after' => $this->userWallet->balance,
                    'metadata' => [
                        'exchange_reference' => $transaction->reference,
                        'from_currency' => $this->exchangeData['baseCurrencyCode'],
                        'to_currency' => $this->exchangeData['quoteCurrencyCode'],
                        'exchange_amount' => $this->exchangeData['quoteAmount']
                    ]
                ]);

                // Send admin notification
                Mail::to('funds@dcashwallet.com')->send(new AdminNotificationMail(
                    'exchange_transaction',
                    Auth::user()->fname,
                    Auth::user()->email,
                    [
                        'transaction_type' => 'Wallet Exchange Transaction',
                        'transaction_amount' => number_format($requiredAmount, 2),
                        'transaction_id' => $transaction->reference,
                        'payment_method' => 'Wallet Payment'
                    ],
                    route('admin.transactions', ['id' => $transaction->id])
                ));

                session()->forget(['exchangeData', 'exchange_expiration_time']);
                return redirect()->route('exchange.completed', ['ref' => $transaction->reference]);
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Payment processing failed. Please try again.');
            \Log::error('Wallet payment failed: '.$e->getMessage());
        }
    }

    public function render()
    {
        $companyBankAccount = CompanyBankAccount::where('currency_id', $this->exchangeData['baseCurrencyId'])
            ->where('is_active', true)
            ->first();


        $this->companyBankAccountId = $companyBankAccount->id;

        // Get all available bank accounts for a currency
        $companyBankAccounts = CompanyBankAccount::where('currency_id', $this->exchangeData['baseCurrencyId'])
            ->where('is_active', true)
            ->orderBy('position', 'ASC');

        // Filter out e-wallet accounts (g-cash, paymaya) for fiat currencies with amounts over 5000
        if ($this->exchangeData['baseCurrencyId'] && $this->exchangeData['baseAmount']) {
            $currencyType = Currency::where('id', $this->exchangeData['baseCurrencyId'])->value('type');
            if ($currencyType === 'fiat' && $this->exchangeData['baseAmount'] > 5000) {
                $companyBankAccounts = $companyBankAccounts->whereNotIn('account_type', ['g-cash', 'paymaya']);
            }
        }

        $companyBankAccounts = $companyBankAccounts->get();

        return view('livewire.app.exchange.payment-page', [
            'companyBankAccount' => $companyBankAccount,
            'deadline' => $this->expirationTime->getPreciseTimestamp(3),
            'companyBankAccounts' => $companyBankAccounts
        ])->layout('layouts.app.app')->title('Exchange Payment');
    }
}
