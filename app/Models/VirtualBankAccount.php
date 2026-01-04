<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VirtualBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency_id',
        'account_number',
        'bank_name',
        'bank_code',
        'account_name',
        'provider',
        'meta',
        'trx_ref',
        'order_ref',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
