<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'type',
        'flag',
        'is_wallet_supported',
        'status',
    ];

    public function fromExchangeTransactions(): HasMany
    {
        return $this->hasMany(ExchangeTransaction::class, 'from_currency_id');
    }

    public function toExchangeTransactions(): HasMany
    {
        return $this->hasMany(ExchangeTransaction::class, 'to_currency_id');
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function companyBankAccounts(): HasMany
    {
        return $this->hasMany(CompanyBankAccount::class);
    }

    public function virtualBankAccounts(): HasMany
    {
        return $this->hasMany(VirtualBankAccount::class);
    }

    public function baseCurrencyPairs(): HasMany
    {
        return $this->hasMany(CurrencyPair::class, 'base_currency_id');
    }

    public function quoteCurrencyPairs(): HasMany
    {
        return $this->hasMany(CurrencyPair::class, 'quote_currency_id');
    }
}
