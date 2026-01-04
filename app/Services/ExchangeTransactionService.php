<?php

namespace App\Services;

use App\Mail\AdminNotificationMail;
use App\Models\ExchangeTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ExchangeTransactionService
{
    public function createTransaction(
        array $exchangeData,
        array $paymentSlips,
        int $companyBankAccountId
    ): ExchangeTransaction {
        // Store all payment slip paths as an array
        $paymentProofPaths = [];
        foreach ($paymentSlips as $slip) {
            $paymentProofPaths[] = $slip->store('payment_proofs', 'public');
        }

        $transaction = ExchangeTransaction::create([
            'reference' => uniqid('EXCH-'),
            'company_bank_account_id' => $companyBankAccountId,
            'user_id' => Auth::id(),
            'from_currency_id' => $exchangeData['baseCurrencyId'] ?? null,
            'to_currency_id' => $exchangeData['quoteCurrencyId'] ?? null,
            'amount_from' => $exchangeData['baseAmount'] ?? 0,
            'amount_to' => $exchangeData['quoteAmount'] ?? 0,
            'rate' => $exchangeData['exchangeRate'] ?? 0,
            'recipient_bank_name' => $exchangeData['bank'] ?? null,
            'recipient_account_number' => $exchangeData['accountNumber'] ?? null,
            'recipient_account_name' => $exchangeData['accountName'] ?? null,
            'recipient_bank_code' => $exchangeData['bankCode'] ?? null,
            'recipient_wallet_address' => $exchangeData['walletAddress'] ?? null,
            'recipient_network' => $exchangeData['network'] ?? null,
            'payment_transaction_hash' => null,
            'payment_proof' => json_encode($paymentProofPaths),
            'status' => 'pending_confirmation',
            'note' => $exchangeData['note'] ?? null,
            'narration' => $exchangeData['narration'] ?? null,
            'cashback' => $exchangeData['baseAmount'] * 0.001
        ]);

        $this->sendAdminNotification($transaction);

        return $transaction;
    }

    private function sendAdminNotification(ExchangeTransaction $transaction): void
    {
        Mail::to('funds@dcashwallet.com')->send(new AdminNotificationMail(
            'exchange_transaction',
            Auth::user()->fname,
            Auth::user()->email,
            [
                'transaction_type' => 'Exchange Transaction',
                'transaction_amount' => number_format($transaction->amount_from, 2),
                'transaction_id' => $transaction->reference,
                'flagged_reason' => 'High amount transaction'
            ],
            route('admin.transactions', ['id' => $transaction->id])
        ));
    }
}
