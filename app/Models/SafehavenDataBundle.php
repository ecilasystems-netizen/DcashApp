<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafehavenDataBundle extends Model
{
    protected $fillable = [
        'network_name',
        'serviceCategoryId',
        'validity',
        'bundle_code',
        'amount',
        'data_size',
        'duration_days',
        'status',
        'is_amount_fixed',
    ];

    // i want to return the amount in naira
    public function getAmountInNairaAttribute(): int
    {
        return (int) ($this->amount / 100);
    }
}
