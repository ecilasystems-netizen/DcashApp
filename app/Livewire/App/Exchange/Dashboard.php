<?php

namespace App\Livewire\App\Exchange;

use App\Models\Currency;
use App\Models\CurrencyPair;
use App\Models\ExchangeTransaction;
use App\Models\KycVerification;
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

    public function acceptTermsAndCreateWallet(VirtualAccountService $virtualAccountService)
    {
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
                $ngnCurrency = Currency::where('code', 'NGN')->first();
                if ($ngnCurrency) {
                    $kycData = [
                        'bvn' => $kycVerification->bvn,
                        'currency_id' => $ngnCurrency->id,
                    ];
                    $virtualAccount = $virtualAccountService->generateAccount($user, $kycData);
                    if (!$virtualAccount) {
                        throw new \Exception('Failed to generate virtual account. Please contact support.');
                    }
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
        }
    }

    public function loadWalletBalance()
    {
        $user = auth()->user();
        // For now, we will display the NGN balance as the total balance.
        $ngnCurrency = Currency::where('code', 'NGN')->first();
        if ($ngnCurrency) {
            $wallet = Wallet::where('user_id', $user->id)
                ->where('currency_id', $ngnCurrency->id)
                ->first();

            if ($wallet) {
                $this->walletBalance = $wallet->balance;
                $this->walletCurrencySymbol = $ngnCurrency->symbol;
            }
        }
    }

    public function mount()
    {
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

        //log the dates
        Log::info("Loading weekly wallet stats for user {$user->id} from {$startOfWeek} to {$endOfWeek}");

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();
        //log the transactions
        Log::info('Weekly Wallet Transactions for user '.$user->id.': '.json_encode($transactions));

        $this->weeklyInflow = $transactions->where('direction', 'credit')->sum('amount');
        $this->weeklyOutflow = $transactions->where('direction', 'debit')->sum('amount');
        //log outline and inflow
        Log::info("Weekly Inflow: {$this->weeklyInflow}, Weekly Outflow: {$this->weeklyOutflow}");

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
        $this->exchangeTransactions = ExchangeTransaction::where('user_id', auth()->id())
            ->with(['fromCurrency', 'toCurrency'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function loadWalletTransactions(int $limit = 3)
    {
        $this->walletTransactions = WalletTransaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

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
        $kycVerification = KycVerification::where('user_id', auth()->id())
            ->latest()
            ->first();

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
        session()->forget('exchange_expiration_time');

        return $this->redirect(route('exchange.enter-bank-account'), navigate: true);
    }

    public function render()
    {
        $currencies = Currency::where('status', 1)->get();
        $rawCurrencyPairs = CurrencyPair::with(['baseCurrency', 'quoteCurrency'])
            ->where('is_active', true)
            ->latest()
            ->take(30)
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
            $buyRate = $pair->baseCurrency->code === $firstCurrency->code
                ? $pair->rate
                : ($pair->rate);

            $reversePair = $rawCurrencyPairs->where('base_currency_id', $secondCurrency->id)
                ->where('quote_currency_id', $firstCurrency->id)
                ->first();

            $sellRate = $reversePair
                ? $reversePair->rate
                : ($buyRate);

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
            //                'PHP-PHP' => 1,
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
}
