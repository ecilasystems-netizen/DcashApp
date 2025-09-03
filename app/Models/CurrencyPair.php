<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_currency_id',
        'quote_currency_id',
        'rate',
        'raw_rate',
        'auto_update',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:6',
        'raw_rate' => 'decimal:6',
        'is_active' => 'boolean',
        'auto_update' => 'boolean',
    ];

    public function baseCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'base_currency_id');
    }

    public function quoteCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'quote_currency_id');
    }
}
