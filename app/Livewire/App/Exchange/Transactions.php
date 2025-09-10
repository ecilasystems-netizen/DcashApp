<?php

namespace App\Livewire\App\Exchange;

use Livewire\Component;

class Transactions extends Component
{
    //on mount, get the transactions for the user
    public $transactions;
    public $search = '';
    public $stats;

    public function mount()
    {
        $activeTab = session('exchange_active_tab', 'exchange'); // default to 'exchange'

        if ($activeTab === 'wallet') {
            $this->transactions = auth()->user()->walletTransactions()
                ->with(['wallet'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->transactions = auth()->user()->exchangeTransactions()
                ->with(['fromCurrency', 'toCurrency'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $this->calculateStats();
    }

    private function calculateStats()
    {
        $thirtyDaysAgo = now()->subDays(30);
        $activeTab = session('exchange_active_tab', 'exchange');

        if ($activeTab === 'wallet') {
            // Calculate stats for wallet transactions
            $this->stats = [
                'total' => $this->transactions->count(),
                'volumes' => collect(), // No currency volumes for wallet transactions
                'successful' => $this->transactions->where('status', 'completed')->count(),
                'pending' => $this->transactions->where('status', 'pending')->count(),
            ];
        } else {
            // Existing exchange transaction stats
            $volumes = $this->transactions
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->groupBy('from_currency_id')
                ->map(function ($transactions) {
                    return [
                        'amount' => $transactions->sum('amount_from'),
                        'currency' => $transactions->first()->fromCurrency
                    ];
                });

            $this->stats = [
                'total' => $this->transactions->count(),
                'volumes' => $volumes,
                'successful' => $this->transactions->where('status', 'completed')->count(),
                'pending' => $this->transactions->whereIn('status',
                    ['pending_payment', 'pending_confirmation'])->count(),
            ];
        }
    }


    public function render()
    {
        return view('livewire.app.exchange.transactions')->layout('layouts.app.app')->title('Transactions History');
    }
}
