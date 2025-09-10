<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\WalletTransaction;
use Livewire\Component;

class ShowWalletTransaction extends Component
{
    public WalletTransaction $transaction;

    public function mount(WalletTransaction $transaction)
    {
        $this->transaction = $transaction->load(['user', 'wallet.currency']);
    }

    public function approveTransaction()
    {
        $this->transaction->update(['status' => 'completed']);
        session()->flash('message', 'Transaction approved successfully.');
        $this->refreshTransaction();
    }

    public function rejectTransaction($reason)
    {
        $this->transaction->update([
            'status' => 'rejected',
            'description' => $this->transaction->description.' | Rejection Reason: '.$reason
        ]);
        session()->flash('message', 'Transaction rejected successfully.');
        $this->refreshTransaction();
    }

    public function deleteTransaction()
    {
        $this->transaction->delete();
        return redirect()->route('admin.wallet-transactions.index')->with('message',
            'Transaction deleted successfully.');
    }

    public function refreshTransaction()
    {
        $this->transaction->refresh();
    }

    public function render()
    {
        return view('livewire.admin.transactions.show-wallet-transaction')->layout('layouts.admin.app', [
            'title' => 'Wallet Transaction #'.$this->transaction->reference,
            'description' => 'Details for wallet transaction '.$this->transaction->reference,
        ]);
    }
}
