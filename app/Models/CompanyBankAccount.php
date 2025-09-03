<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'bank_name',
        'account_number',
        'account_name',
        'account_type',
        'bank_account_qr_code',
        'is_crypto',
        'crypto_wallet_address',
        'crypto_network',
        'crypto_name',
        'crypto_qr_code',
        'is_active',
        'is_crypto',
        'qr'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }


    // Adjust foreign key if your column is different (e.g. bank_account_id)
    public function transactions(): HasMany
    {
        return $this->hasMany(ExchangeTransaction::class, 'company_bank_account_id');
    }
}
