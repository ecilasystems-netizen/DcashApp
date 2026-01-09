<?php

namespace App\Livewire\App\Exchange;

use App\Models\Announcement;
use App\Models\Currency;
use App\Models\CurrencyPair;
use App\Models\ExchangeTransaction;
use App\Models\KycVerification;
use App\Models\VirtualBankAccount;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\VirtualAccountService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboard extends Component
{
    public $baseCurrency = 'PHP';
    public $quoteCurrency = 'NGN';
    public $baseAmount = "";
    public $quoteAmount = "";
    public $activityStats;
    public $recentTransactions;
    public $activeTab;
    public $showTermsModal = false;
    public $hasWallet = false;
    public $showSuccessModal = false;
    public $showErrorModal = false;
    public $modalMessage = '';
    public $walletBalance = 0;
    public $walletCurrencySymbol = '₦';
    public $exchangeTransactions = [];
    public $walletTransactions = [];
    public $weeklyInflow = 0;
    public $weeklyOutflow = 0;
    public $weeklyChartData = [];
    public $maxWeeklyFlow = 0;
    public $bvn;
    public $fullName;
    public $dateOfBirth;
    public $safeHavenAccountCreated = false;

    public $sliderAnnouncements;
    public $imageAnnouncements;
    public $notifications;
    public $unreadNotifications = 0;

    protected $listeners = ['echo:notifications,TransactionCreated' => 'refreshNotifications'];


    public function mount()
    {
        //check if user virtual ACCOUNT PROVIDER IS SAFEWHAVEN
        $user = auth()->user();
        $virtualAccount = VirtualBankAccount::where('user_id', $user->id)->first();
        if ($virtualAccount && $virtualAccount->provider === 'safehaven') {
            $this->safeHavenAccountCreated = true;
        }

        // Get active tab from session or default to 'exchange'
        $this->activeTab = session('exchange_active_tab', 'exchange');

        $this->checkWallet();
        if ($this->hasWallet) {
            $this->loadWalletBalance();
            $this->loadWeeklyWalletStats();
        }

        $this->calculateQuoteAmount();
        $this->loadActivityStats();
        $this->loadRecentTransactions();
        $this->loadExchangeTransactions(3);
        $this->loadWalletTransactions(3);
        $this->showTermsModal = false;

        // Update to use correct content_type values
        $this->sliderAnnouncements = Announcement::where('content_type', 'slider')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->whereNotNull('content')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $this->imageAnnouncements = Announcement::where('content_type', 'image')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->whereNotNull('content')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $this->loadNotifications();
    }

    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function acceptTermsAndCreateWallet(VirtualAccountService $virtualAccountService)
    {
        // Validate required fields before proceeding
        $this->validate([
            'fullName' => 'required|string|min:2|max:255',
            'dateOfBirth' => 'required|date|before:today',
            'bvn' => 'required|string|size:11|regex:/^[0-9]+$/',
        ], [
            'fullName.required' => 'Full name is required.',
            'fullName.min' => 'Full name must be at least 2 characters.',
            'dateOfBirth.required' => 'Date of birth is required.',
            'dateOfBirth.before' => 'Date of birth must be in the past.',
            'bvn.required' => 'BVN is required.',
            'bvn.size' => 'BVN must be exactly 11 digits.',
            'bvn.regex' => 'BVN must contain only numbers.',
        ]);

        try {


            $user = auth()->user();
            $kycVerification = KycVerification::where('user_id', $user->id)
                ->where('status', 'approved')
                ->latest()
                ->first();

            if (!$kycVerification) {
                return $this->redirect(route('kyc.start'), navigate: true);
            }

            if ($kycVerification->nationality === 'Nigeria') {

                //update the user KYC with the bvn
                $kycVerification->bvn = $this->bvn;
                $kycVerification->save();

                $ngnCurrency = Currency::where('code', 'NGN')->first();
                if ($ngnCurrency) {
                    $kycData = [
                        'bvn' => $this->bvn,
                        'currency_id' => $ngnCurrency->id,
                    ];
                    $virtualAccount = $virtualAccountService->generateAccount($user, $kycData);
                    if (!$virtualAccount) {
                        throw new \Exception('Failed to generate virtual account. Please contact support.');
                    }
                }

                // Create wallet for user
                $currency = Currency::find(1);
                Wallet::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'currency_id' => $currency->id,
                    ],
                    [
                        'balance' => 0.00,
                    ]
                );

                $this->showTermsModal = false;
                $this->modalMessage = 'Your wallet has been created successfully!';
                $this->showSuccessModal = true;
            }


        } catch (\Exception $e) {
            Log::error('Failed to create wallet for user '.$user->id.': '.$e->getMessage());
            $this->showTermsModal = false;
            $this->modalMessage = $e->getMessage() ?: 'An unexpected error occurred. Please try again later.';
            $this->showErrorModal = true;
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->hasWallet = true;
        $this->activeTab = 'wallet';
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
    }

    public function setActiveTab($tab)
    {
        if ($tab === 'wallet') {
            //check user KYC status
            $this->checkKycStatus();

            $this->checkWallet();
            if (!$this->hasWallet) {
                // Don't switch tab yet
                return;
            }
        }

        $this->checkWallet();
        if ($this->hasWallet) {
            $this->loadWalletBalance();
            $this->loadWeeklyWalletStats();
        }

        $this->activeTab = $tab;
        session(['exchange_active_tab' => $tab]); // Save to session
    }

    public function checkWallet()
    {
        $this->hasWallet = Wallet::where('user_id', auth()->id())->exists();
        if (!$this->hasWallet) {

            $this->showTermsModal = true;
            // Dispatch event to open the child modal component
            $this->dispatch('openModal');

        }
    }

    public function loadWalletBalance()
    {
        $user = auth()->user();
        // For now, we will display the NGN balance as the total balance.
        $ngnCurrency = Currency::where('code', 'NGN')->first();
        if ($ngnCurrency) {
            $wallet = Wallet::where('user_id', $user->id)
                ->where('currency_id', $ngnCurrency->id)->first();

            if ($wallet) {
                $this->walletBalance = $wallet->balance;
                $this->walletCurrencySymbol = $ngnCurrency->symbol;
            }
        }
    }


    public function formatNumberShort($num): string
    {
        if ($num >= 1000000) {
            return round($num / 1000000, 1).'M';
        }
        if ($num >= 1000) {
            return round($num / 1000, 1).'K';
        }
        return (string) $num;
    }

    private function loadWeeklyWalletStats()
    {
        $user = auth()->user();
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();


        $transactions = WalletTransaction::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();


        $this->weeklyInflow = $transactions->where('direction', 'credit')->sum('amount');
        $this->weeklyOutflow = $transactions->where('direction', 'debit')->sum('amount');


        $dailyStats = $transactions->groupBy(function ($date) {
            return \Carbon\Carbon::parse($date->created_at)->format('D');
        })->map(function ($dayTransactions) {
            return [
                'inflow' => $dayTransactions->where('direction', 'credit')->sum('amount'),
                'outflow' => $dayTransactions->where('direction', 'debit')->sum('amount'),
            ];
        });

        $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $chartData = [];
        $maxFlow = 0;
        foreach ($weekDays as $day) {
            $inflow = $dailyStats[$day]['inflow'] ?? 0;
            $outflow = $dailyStats[$day]['outflow'] ?? 0;
            $chartData[$day] = ['inflow' => $inflow, 'outflow' => $outflow];
            $maxFlow = max($maxFlow, $inflow, $outflow);
        }

        $this->weeklyChartData = $chartData;
        $this->maxWeeklyFlow = $maxFlow > 0 ? $maxFlow : 1; // Avoid division by zero
    }

    public function calculateQuoteAmount()
    {
        $currencyPair = CurrencyPair::where('base_currency_id', $this->getCurrencyId($this->baseCurrency))
            ->where('quote_currency_id', $this->getCurrencyId($this->quoteCurrency))
            ->first();

        if ($currencyPair && $this->baseAmount > 0) {
            $this->quoteAmount = number_format($this->baseAmount * $currencyPair->raw_rate, 2, '.', '');
        } else {
            $this->quoteAmount = number_format(0, 2, '.', ''); // Default to 0.00 if no rate is found
        }
    }

    private function getCurrencyId($currencyCode)
    {
        return Currency::where('code', $currencyCode)->value('id');
    }

    private function loadActivityStats()
    {
        $userId = auth()->id();

        $this->activityStats = [
            'total_exchanges' => ExchangeTransaction::where('user_id', $userId)->count(),
            'successful' => ExchangeTransaction::where('user_id', $userId)
                ->where('status', 'completed')
                ->count(),
            'volume' => ExchangeTransaction::where('user_id', $userId)
                ->where('status', 'completed')
                ->sum('amount_from'),
            'days_active' => ExchangeTransaction::where('user_id', $userId)
                ->distinct()
                ->selectRaw('DATE(created_at)')
                ->count()
        ];
    }

    private function loadRecentTransactions()
    {
        $this->recentTransactions = ExchangeTransaction::where('user_id', auth()->id())
            ->with(['fromCurrency', 'toCurrency'])
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
    }

    public function loadExchangeTransactions(int $limit = 3)
    {
        $exchangeTransactions = ExchangeTransaction::where('user_id', auth()->id())
            ->with(['fromCurrency', 'toCurrency'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_type' => 'exchange',
                    'reference' => $transaction->reference,
                    'amount_from' => $transaction->amount_from,
                    'amount_to' => $transaction->amount_to,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at,
                    'from_currency' => $transaction->fromCurrency,
                    'to_currency' => $transaction->toCurrency,
                ];
            });

        $bonusTransactions = auth()->user()->bonuses()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($bonus) {
                return [
                    'id' => $bonus->id,
                    'transaction_type' => 'bonus',
                    'bonus_amount' => $bonus->bonus_amount,
                    'type' => $bonus->type,
                    'status' => $bonus->status,
                    'trigger_event' => $bonus->trigger_event,
                    'notes' => $bonus->notes,
                    'created_at' => $bonus->created_at,
                ];
            });

        $this->exchangeTransactions = $exchangeTransactions
            ->concat($bonusTransactions)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function loadWalletTransactions(int $limit = 3)
    {
        $walletTransactions = WalletTransaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_type' => 'wallet',
                    'amount' => $transaction->amount,
                    'direction' => $transaction->direction,
                    'status' => $transaction->status,
                    'reference' => $transaction->reference,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                ];
            });

        $bonusTransactions = auth()->user()->bonuses()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($bonus) {
                return [
                    'id' => $bonus->id,
                    'transaction_type' => 'bonus',
                    'bonus_amount' => $bonus->bonus_amount,
                    'type' => $bonus->type,
                    'status' => $bonus->status,
                    'trigger_event' => $bonus->trigger_event,
                    'notes' => $bonus->notes,
                    'created_at' => $bonus->created_at,
                ];
            });

        $this->walletTransactions = $walletTransactions
            ->concat($bonusTransactions)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function getVolumeByBaseCurrencyProperty()
    {
        return ExchangeTransaction::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->get()
            ->groupBy('fromCurrency.code')
            ->map(function ($transactions) {
                return $transactions->sum('amount_from');
            });
    }

    public function updatedBaseAmount()
    {
        $this->calculateQuoteAmount();
    }

    public function updatedBaseCurrency($value)
    {
        if ($value === $this->quoteCurrency) {
            // Pick the first different currency as quote
            $this->quoteCurrency = Currency::where('code', '!=', $value)->value('code');
        }
        $this->calculateQuoteAmount();
        $this->dispatch('currencyChanged');
    }

    public function updatedQuoteCurrency($value)
    {
        if ($value === $this->baseCurrency) {
            // Pick the first different currency as base
            $this->baseCurrency = Currency::where('code', '!=', $value)->value('code');
        }
        $this->calculateQuoteAmount();
        $this->dispatch('currencyChanged');
    }

    public function swapCurrencies()
    {
        // Swap the base and quote currencies
        $temp = $this->baseCurrency;
        $this->baseCurrency = $this->quoteCurrency;
        $this->quoteCurrency = $temp;

        $this->calculateQuoteAmount();
        $this->dispatch('currencyChanged');
    }

    public function exchangeNow()
    {

        // Clear old session data first
        session()->forget(['exchangeData', 'exchange_expiration_time']);


        $kycVerification = KycVerification::where('user_id', auth()->id())
            ->latest()
            ->first();

        //i want to set the minimum amount for exchange base on the currency and check if the base amount is less than the minimum amount then display an error message
        $minimumAmounts = [
            'PHP' => 500, // Minimum amount for PHP
            'NGN' => 14000, // Minimum amount for NGN
            'USD' => 50, // Minimum amount for USD
            'USDT' => 50, // Minimum amount for USDT
            // Add more currencies and their minimum amounts as needed
        ];
        $minAmount = $minimumAmounts[$this->baseCurrency] ?? 0;
        if ($this->baseAmount < $minAmount) {
            $this->modalMessage = "The minimum amount for {$this->baseCurrency} is {$minAmount}. Please enter a valid amount.";
            $this->showErrorModal = true;
            return null;
        }


        // Direct redirect checks in the action method
        if (!$kycVerification) {
            return $this->redirect(route('kyc.start'), navigate: true);
        }

        if ($kycVerification->status === 'pending') {
            return $this->redirect(route('kyc.under-review'), navigate: true);
        }

        if ($kycVerification->status === 'rejected') {
            return $this->redirect(route('kyc.start'), navigate: true);
        }

        // Continue if approved
        $currencyPair = CurrencyPair::where('base_currency_id', $this->getCurrencyId($this->baseCurrency))
            ->where('quote_currency_id', $this->getCurrencyId($this->quoteCurrency))
            ->first();

        $exchangeData = [
            'baseCurrencyId' => $this->getCurrencyId($this->baseCurrency),
            'quoteCurrencyId' => $this->getCurrencyId($this->quoteCurrency),
            'baseAmount' => $this->baseAmount,
            'quoteAmount' => $this->quoteAmount,
            'baseCurrencyCode' => $this->baseCurrency,
            'quoteCurrencyCode' => $this->quoteCurrency,
            'exchangeRate' => $currencyPair ? $currencyPair->rate : 0,
            'baseCurrencyFlag' => Currency::where('code', $this->baseCurrency)->value('flag'),
            'quoteCurrencyFlag' => Currency::where('code', $this->quoteCurrency)->value('flag'),
        ];

        session(['exchangeData' => $exchangeData]);
//        session()->forget('exchange_expiration_time');

        return $this->redirect(route('exchange.enter-bank-account'), navigate: true);
    }

    public function render()
    {
        $currencies = Currency::where('status', 1)->get();
        $rawCurrencyPairs = CurrencyPair::with(['baseCurrency', 'quoteCurrency'])
            ->where('is_active', true)
            ->latest()
            ->take(900)
            ->get();

        // Process pairs for consistent display order
        $processedPairs = collect();
        $displayedPairKeys = [];

        foreach ($rawCurrencyPairs as $pair) {
            [$firstCurrency, $secondCurrency] = $this->getDisplayOrder(
                $pair->baseCurrency,
                $pair->quoteCurrency
            );

            $pairKey = $firstCurrency->code.'-'.$secondCurrency->code;

            // Skip if we've already processed this pair
            if (in_array($pairKey, $displayedPairKeys)) {
                continue;
            }

            $displayedPairKeys[] = $pairKey;

            // Find rates for both directions
            $mainPair = $rawCurrencyPairs->where('base_currency_id', $firstCurrency->id)
                ->where('quote_currency_id', $secondCurrency->id)
                ->first();
            $buyRate = $mainPair->rate;

            $reversePair = $rawCurrencyPairs->where('base_currency_id', $secondCurrency->id)
                ->where('quote_currency_id', $firstCurrency->id)
                ->first();

            $sellRate = $reversePair->rate;


            // Create a processed pair object
            $processedPair = (object) [
                'firstCurrency' => $firstCurrency,
                'secondCurrency' => $secondCurrency,
                'buyRate' => $buyRate,
                'sellRate' => $sellRate,
                'pairKey' => $pairKey,
                'priority' => $this->getPairPriority($firstCurrency, $secondCurrency)
            ];

            $processedPairs->push($processedPair);
        }

        // Sort by priority (highest first) and take only the first 5
        $processedPairs = $processedPairs->sortByDesc('priority');

        return view('livewire.app.exchange.dashboard', compact('currencies', 'processedPairs'))
            ->layout('layouts.app.app')
            ->title('Dashboard');
    }

    private function getDisplayOrder($currency1, $currency2)
    {
        // Define your preferred currency order (higher priority comes first)
        $currencyPriority = [
            'USDT' => 100,
            'USD' => 90,
            'PHP' => 80,
            'RMB' => 70,
            'USDC' => 70,
            'NGN' => 60,
            'GHC' => 50,
            'BTC' => 40,
            'ETH' => 30,
            // Add more currencies as needed
        ];

        $priority1 = $currencyPriority[$currency1->code] ?? 0;
        $priority2 = $currencyPriority[$currency2->code] ?? 0;

        // Return currencies in priority order (higher priority first)
        return $priority1 >= $priority2
            ? [$currency1, $currency2]
            : [$currency2, $currency1];
    }

    private function getPairPriority($firstCurrency, $secondCurrency)
    {
        // Define your top 5 pairs in order of priority
        $priorityPairs = [
            'PHP-NGN' => 5,
            'USDT-NGN' => 4,
            'USD-NGN' => 3,
            'USDT-PHP' => 2,
            'USDC-NGN' => 1,
        ];

        $pairKey = $firstCurrency->code.'-'.$secondCurrency->code;
        return $priorityPairs[$pairKey] ?? 0;
    }

    private function checkKycStatus()
    {
        $kycVerification = KycVerification::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$kycVerification) {
            return $this->redirect(route('kyc.start'), navigate: true);
        }

        if ($kycVerification->status === 'pending') {
            return $this->redirect(route('kyc.under-review'), navigate: true);
        }

        if ($kycVerification->status === 'rejected') {
            return $this->redirect(route('kyc.start'), navigate: true);
        }

        // Add this line to explicitly return null when KYC is approved
        return null;
    }

    public function viewExchangeTransaction($transactionId)
    {
        // Option 1: Redirect to transaction details page

        // Route::get('/exchange/receipt/{ref}', ExchangeReceipt::class)->name('exchange.receipt');
        return redirect()->route('exchange.receipt', $transactionId);

        // Option 2: Open modal with transaction details
        // $this->selectedTransaction = ExchangeTransaction::find($transactionId);
        // $this->showTransactionModal = true;
    }


    public function loadNotifications()
    {

    }

    public function markNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->unreadNotifications = 0;
    }

    public function clearAllNotifications()
    {
        auth()->user()->notifications()->delete();
        $this->loadNotifications();
    }

    public function viewTransaction($transactionId)
    {
        return redirect()->route('wallet.transactions.show', $transactionId);
    }

    public function viewAllNotifications()
    {
        return redirect()->route('notifications.index');
    }
}
