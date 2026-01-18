<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafehavenTvBundle extends Model
{
    protected $fillable = [
        'name',
        'serviceCategoryId',
        'network_code',
        'bundleCode',
        'amount',
        'duration',
        'status',
        'isAmountFixed',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'duration' => 'integer',
        'isAmountFixed' => 'boolean',
        'status' => 'boolean',
    ];


    /**
     * Scope to get only active bundles
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to filter by network code
     */
    public function scopeForNetwork($query, string $networkCode)
    {
        return $query->where('network_code', $networkCode);
    }

    /**
     * Scope to filter by service category ID (TV Provider)
     */
    public function scopeForProvider($query, string $providerId)
    {
        return $query->where('serviceCategoryId', $providerId);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₦' . number_format((float) $this->amount, 2);
    }

    /**
     * Get duration in human-readable format
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration === 7) {
            return '1 Week';
        }

        if ($this->duration === 30) {
            return '1 Month';
        }

        if ($this->duration === 90) {
            return '3 Months';
        }

        if ($this->duration === 365) {
            return '1 Year';
        }

        return "{$this->duration} Days";
    }

}
