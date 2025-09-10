<?php

namespace App\Livewire\App\Wallet\Transactions;

use App\Models\WalletTransaction;
use Livewire\Component;

class Receipt extends Component
{
    public $reference;
    public $transaction;
    public $backUrl;

    public function mount($ref)
    {
        $this->reference = $ref;
        $this->backUrl = route('exchange.transactions');

        $transaction = WalletTransaction::where('reference', $this->reference)
            ->where('user_id', auth()->id())
            ->with('wallet.currency', 'user')
            ->first();

        if (!$transaction) {
            session()->flash('error', 'Transaction not found or you do not have permission to view it.');
            return redirect($this->backUrl);
        }

        $this->transaction = $transaction;
    }

    public function render()
    {
        return view('livewire.app.wallet.transactions.receipt')
            ->layout('layouts.app.app')
            ->title('Wallet Transaction Receipt');
    }
}
