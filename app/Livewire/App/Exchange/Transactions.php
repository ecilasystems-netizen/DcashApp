<?php

namespace App\Livewire\App\Exchange;

use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;

    public $search = '';
    public $stats;
    public $perPage = 50;

    public function mount()
    {
        $this->calculateStats();
    }

    private function getTransactions()
    {
        $activeTab = session('exchange_active_tab', 'exchange');

        if ($activeTab === 'wallet') {
            $walletTransactions = auth()->user()->walletTransactions()
                ->with(['wallet'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'transaction_type' => 'wallet',
                        'reference' => $transaction->reference,
                        'amount' => $transaction->amount,
                        'charge' => $transaction->charge,
                        'direction' => $transaction->direction,
                        'type' => $transaction->type,
                        'status' => $transaction->status,
                        'description' => $transaction->description,
                        'created_at' => $transaction->created_at,
                        'currency_symbol' => $transaction->wallet->currency->symbol ?? '₦',
                        'currency_code' => $transaction->wallet->currency->code ?? 'NGN',
                    ];
                });

            $bonusTransactions = auth()->user()->bonuses()
                ->with(['referralBonus'])
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

            return $walletTransactions
                ->concat($bonusTransactions)
                ->sortByDesc('created_at')
                ->values();
        } else {
            $exchangeTransactions = auth()->user()->exchangeTransactions()
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
                        'from_currency_code' => $transaction->fromCurrency->code,
                        'from_currency_symbol' => $transaction->fromCurrency->symbol,
                        'to_currency_code' => $transaction->toCurrency->code,
                        'to_currency_symbol' => $transaction->toCurrency->symbol,
                    ];
                });

            $bonusTransactions = auth()->user()->bonuses()
                ->with(['referralBonus'])
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

            return $exchangeTransactions
                ->concat($bonusTransactions)
                ->sortByDesc('created_at')
                ->values();
        }
    }

    private function calculateStats()
    {
        $thirtyDaysAgo = now()->subDays(30);
        $activeTab = session('exchange_active_tab', 'exchange');
        $transactions = $this->getTransactions();

        if ($activeTab === 'wallet') {
            $this->stats = [
                'total' => $transactions->count(),
                'volumes' => collect(),
                'successful' => $transactions->where('status', 'completed')->count(),
                'pending' => $transactions->where('status', 'pending')->count(),
            ];
        } else {
            $volumes = $transactions
                ->filter(function ($transaction) use ($thirtyDaysAgo) {
                    return $transaction['transaction_type'] === 'exchange'
                        && $transaction['created_at'] >= $thirtyDaysAgo;
                })
                ->groupBy('from_currency_code')
                ->map(function ($transactions, $currencyCode) {
                    $totalAmount = $transactions->sum('amount_from');
                    return [
                        'currency' => (object) [
                            'code' => $currencyCode,
                            'symbol' => $transactions->first()['from_currency_symbol']
                        ],
                        'amount' => $totalAmount
                    ];
                });

            $this->stats = [
                'total' => $transactions->count(),
                'volumes' => $volumes,
                'successful' => $transactions->where('status', 'completed')->count(),
                'pending' => $transactions->whereIn('status',
                    ['pending', 'pending_payment', 'pending_confirmation'])->count(),
            ];
        }
    }

    public function render()
    {
        $transactions = $this->getTransactions()
            ->forPage($this->getPage(), $this->perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $transactions,
            $this->getTransactions()->count(),
            $this->perPage,
            $this->getPage(),
            ['path' => request()->url()]
        );

        return view('livewire.app.exchange.transactions', [
            'transactions' => $paginator
        ])->layout('layouts.app.app')->title('Transactions History');
    }
}
