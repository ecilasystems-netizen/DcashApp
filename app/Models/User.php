<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'username',
        'email',
        'phone',
        'password',
        'kyc_status',
        'is_admin',
        'referral_code',
        'referred_by',
        'pin',
        'account_tier_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function exchangeTransactions(): HasMany
    {
        return $this->hasMany(ExchangeTransaction::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }


    public function virtualBankAccount(): HasOne
    {
        return $this->hasOne(VirtualBankAccount::class)->where('is_active', true);
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function virtualBankAccounts(): HasMany
    {
        return $this->hasMany(VirtualBankAccount::class);
    }

    /**
     * Get all Kyc submission attempts for the user.
     */
    public function kycVerifications(): HasMany
    {
        return $this->hasMany(KycVerification::class);
    }

    /**
     * Get the latest Kyc submission for the user.
     */
    public function latestKyc(): HasOne
    {
        return $this->hasOne(KycVerification::class)->latestOfMany();
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //referred by user
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by', 'referral_code');
    }

    //wallet transactions
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // Add this method to your User model

    /**
     * Get all referral bonuses earned by this user
     */
    public function referralBonuses(): HasMany
    {
        return $this->hasMany(ReferralBonus::class, 'referrer_id');
    }

    /**
     * Get all referral bonuses where this user was the referred user
     */
    public function referredBonuses(): HasMany
    {
        return $this->hasMany(ReferralBonus::class, 'referred_user_id');
    }

    /**
     * Get total credited referral bonus amount
     */
    public function getTotalReferralBonusAttribute(): float
    {
        return $this->referralBonuses()->credited()->sum('bonus_amount');
    }
}
