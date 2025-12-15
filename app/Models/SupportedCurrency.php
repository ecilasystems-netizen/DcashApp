<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportedCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'type',
        'is_active',
        'min_redemption',
        'networks',
        'banks',
        'exchange_rate',
        'instructions',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'networks' => 'array',
        'banks' => 'array',
        'exchange_rate' => 'decimal:4',
        'min_redemption' => 'integer',
        'sort_order' => 'integer'
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFiat(Builder $query): Builder
    {
        return $query->where('type', 'fiat');
    }

    public function scopeCrypto(Builder $query): Builder
    {
        return $query->where('type', 'crypto');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function isFiat(): bool
    {
        return $this->type === 'fiat';
    }

    public function isCrypto(): bool
    {
        return $this->type === 'crypto';
    }

    public function hasNetworks(): bool
    {
        return $this->isCrypto() && !empty($this->networks);
    }

    public function hasBanks(): bool
    {
        return $this->isFiat() && !empty($this->banks);
    }

    public function getNetworksListAttribute(): array
    {
        if (!$this->hasNetworks()) {
            return [];
        }

        return $this->networks;
    }

    public function getBanksListAttribute(): array
    {
        if (!$this->hasBanks()) {
            return [];
        }

        return $this->banks;
    }

    public function redemptionRequests()
    {
        return $this->hasMany(RedemptionRequest::class, 'currency', 'code');
    }
}
