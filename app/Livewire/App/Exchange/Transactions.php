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
        $this->transactions = auth()->user()->exchangeTransactions()
            ->with(['fromCurrency', 'toCurrency'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->calculateStats();
    }

    private function calculateStats()
    {
        $thirtyDaysAgo = now()->subDays(30);

        // Group transactions by currency for volume calculation
        $volumes = $this->transactions
            ->where('created_at', '>=', $thirtyDaysAgo)
//            ->where('status', 'completed')
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
            'pending' => $this->transactions->whereIn('status', ['pending_payment', 'pending_confirmation'])->count(),
        ];
    }


    public function render()
    {
        return view('livewire.app.exchange.transactions')->layout('layouts.app.app')->title('Transactions History');
    }
}
