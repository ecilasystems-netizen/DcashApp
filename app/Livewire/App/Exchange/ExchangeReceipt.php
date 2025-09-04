<?php

namespace App\Livewire\App\Exchange;

use App\Models\ExchangeTransaction;
use Livewire\Component;

class ExchangeReceipt extends Component
{
    public $reference;
    public $transactionData = [];
    public $backUrl;

    public function mount($ref, $backUrl = null)
    {
        $this->reference = $ref;

        $this->backUrl = $backUrl ?? route('exchange.transactions');


        // Check if the reference is valid
        $transaction = ExchangeTransaction::where('reference', $this->reference)
            ->where('user_id', auth()->id())
            ->first();
        if (!$transaction) {
            return redirect()->route('dashboard')->with('error',
                'Transaction not found or you do not have permission to view it.');
        }

        $this->transactionData = [
            'reference' => $this->reference,
            'baseCurrencyCode' => $transaction->fromCurrency->code,
            'quoteCurrencyCode' => $transaction->toCurrency->code,
            'exchangeRate' => $transaction->rate,
            'baseAmount' => $transaction->amount_from,
            'quoteAmount' => $transaction->amount_to,
            'baseCurrencyFlag' => $transaction->fromCurrency->flag,
            'quoteCurrencyFlag' => $transaction->toCurrency->flag,
            'recipientBankName' => $transaction->recipient_bank_name,
            'recipientAccountNumber' => substr_replace($transaction->recipient_account_number, '******', 0, 6),
            'recipientAccountName' => $transaction->recipient_account_name,
            'transactionDate' => $transaction->created_at->format('Y-m-d H:i:s'),
            'senderName' => $transaction->user->fname.' '.$transaction->user->lname,
            'status' => $transaction->status,
            'recipientWalletAddress' => $transaction->recipient_wallet_address ?? null,
            'recipientNetwork' => $transaction->recipient_network ?? null,
            'paymentTransactionHash' => $transaction->payment_transaction_hash ?? null,
            'paymentProof' => json_decode($transaction->payment_proof, true) ?? [],
            'companyBankAccount' => $transaction->companyBankAccount ? [
                'name' => $transaction->companyBankAccount->name,
                'accountNumber' => $transaction->companyBankAccount->account_number,
                'bankName' => $transaction->companyBankAccount->bank_name,
            ] : null,

        ];
    }

    public function downloadReceipt()
    {
        $this->dispatch('captureAndDownload');
    }

    public function shareReceipt()
    {
        $this->dispatch('captureAndShare');
    }

    public function render()
    {
        return view('livewire.app.exchange.exchange-receipt')->layout('layouts.app.app')->title('Transaction Receipt');
    }
}
