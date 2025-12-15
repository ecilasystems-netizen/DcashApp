<?php

namespace App\Livewire\Rewards;

use App\Models\ExchangeTransaction;
use Livewire\Component;

class IndexCashbacks extends Component
{
    public $transactions;
    public $totalCashbackByCurrency;
    public $backUrl;

    public function mount($backUrl = null)
    {
        $this->transactions = ExchangeTransaction::where('user_id', auth()->id())
            ->whereNotNull('cashback')
            ->where('cashback', '>', 0)
            ->with(['fromCurrency', 'toCurrency'])
            ->latest()
            ->get();

        $this->totalCashbackByCurrency = $this->transactions
            ->groupBy('fromCurrency.code')
            ->map(function ($group) {
                return [
                    'amount' => $group->sum('cashback'),
                    'symbol' => $group->first()->fromCurrency->symbol,
                ];
            });
        $this->backUrl = $backUrl ?? route('rewards.cashbacks');
    }

    public function render()
    {
        return view('livewire.rewards.index-cashbacks')
            ->layout('layouts.app.app')
            ->title('Cashback Rewards');
    }
}
