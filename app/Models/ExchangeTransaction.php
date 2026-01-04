<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'company_bank_account_id',
        'user_id',
        'from_currency_id',
        'to_currency_id',
        'amount_from',
        'amount_to',
        'rate',
        'recipient_bank_name',
        'recipient_bank_code',
        'recipient_account_number',
        'recipient_account_name',
        'recipient_wallet_address',
        'recipient_network',
        'payment_transaction_hash',
        'payment_proof',
        'status',
        'note',
        'narration',
        'cashback',
        'agent_id',
        'device_info'
    ];

    protected $casts = [
        'amount_from' => 'decimal:8',
        'amount_to' => 'decimal:8',
        'rate' => 'decimal:8',
        'status' => 'string',
        'note' => 'array',
        'device_info' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fromCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
