<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RedemptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
        'amount',
        'equivalent_amount',
        'exchange_rate',
        'status',
        'reference',
        'bank_details',
        'wallet_details',
        'processed_at',
        'notes',
        'device_info'
    ];

    protected $casts = [
        'bank_details' => 'array',
        'wallet_details' => 'array',
        'processed_at' => 'datetime',
        'device_info' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
