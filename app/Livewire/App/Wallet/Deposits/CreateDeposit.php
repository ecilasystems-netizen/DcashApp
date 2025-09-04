<?php

namespace App\Livewire\App\Wallet\Deposits;

use Livewire\Component;

class CreateDeposit extends Component
{
    public $accountNumber;
    public $accountName;
    public $bankName;

    public function mount()
    {
        $virtualAccount = auth()->user()->virtualBankAccount()->where('is_active', true)->first();
        $this->accountNumber = $virtualAccount->account_number ?? '0000000000';
        $this->accountName = $virtualAccount->account_name ?? 'John Doe';
        $this->bankName = $virtualAccount->bank_name ?? 'Example Bank';
    }

    public function render()
    {
        return view('livewire.app.wallet.deposits.create-deposit')->layout('layouts.app.app')->title('Make a Deposit');
    }
}
