<?php

namespace App\Livewire\App\Exchange;

use App\Models\ExchangeTransaction;
use Livewire\Component;

class ExchangeCompleted extends Component
{

    public $reference;

    public function mount($ref)
    {
        $this->reference = $ref;

        // Check if the reference is valid
        $transaction = ExchangeTransaction::where('reference', $this->reference)
            ->where('user_id', auth()->id())
            ->first();
        if (!$transaction) {
            return redirect()->route('dashboard')->with('error',
                'Transaction not found or you do not have permission to view it.');
        }

        session([
            'reference' => $this->reference,
            'baseCurrencyCode' => $transaction->fromCurrency->code,
            'quoteCurrencyCode' => $transaction->toCurrency->code,
            'exchangeRate' => $transaction->rate,
            'baseAmount' => $transaction->amount_from,
            'quoteAmount' => $transaction->amount_to,
            'baseCurrencyFlag' => $transaction->fromCurrency->flag,
            'quoteCurrencyFlag' => $transaction->toCurrency->flag,
            'recipientBankName' => $transaction->recipient_bank_name,
            'recipientAccountNumber' => $transaction->recipient_account_number,
            'recipientAccountName' => $transaction->recipient_account_name,
            'cashback_amount' => $transaction->cashback ?? null,
        ]);

    }


    public function render()
    {
        return view('livewire.app.exchange.exchange-completed')->layout('layouts.app.app')->title('Transaction Completed');
    }
}
