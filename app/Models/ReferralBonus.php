<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'bonus_amount',
        'status',
        'trigger_event',
        'credited_at',
        'notes'
    ];

    protected $casts = [
        'bonus_amount' => 'decimal:2',
        'credited_at' => 'datetime'
    ];

    /**
     * Get the user who made the referral
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the user who was referred
     */
    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    /**
     * Scope to get credited bonuses only
     */
    public function scopeCredited($query)
    {
        return $query->where('status', 'credited');
    }

    /**
     * Scope to get pending bonuses only
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Credit the bonus and update status
     */
    public function credit()
    {
        $this->update([
            'status' => 'credited',
            'credited_at' => now()
        ]);
    }

    /**
     * Revoke the bonus
     */
    public function revoke($reason = null)
    {
        $this->update([
            'status' => 'revoked',
            'notes' => $reason
        ]);
    }
}
