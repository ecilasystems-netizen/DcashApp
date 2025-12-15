<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bonus extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referralBonus(): BelongsTo
    {
        return $this->belongsTo(ReferralBonus::class);
    }

    protected function casts(): array
    {
        return [
            'is_referral_bonus' => 'boolean',
        ];
    }

    protected $fillable = [
        'user_id',
        'referral_bonus_id',
        'bonus_amount',
        'type',
        'status',
        'trigger_event',
        'notes',
    ];
}
