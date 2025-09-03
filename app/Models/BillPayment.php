<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'reference',
        'flw_reference',
        'biller_code',
        'biller_name',
        'item_code',
        'category',
        'customer_id',
        'customer_name',
        'amount',
        'currency',
        'country',
        'status',
        'type',
        'description',
        'recurrence',
        'fee',
        'flw_response',
        'callback_url',
        'paid_at',
        'failure_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'flw_response' => 'array',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'flw_response', // Hide sensitive Flutterwave response data
    ];

    /**
     * Generate a unique reference for the payment.
     */
    public static function generateReference(string $prefix = 'BP'): string
    {
        do {
            $reference = $prefix . '_' . time() . '_' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Get the user that owns the bill payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include successful payments.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'successful');
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to filter by biller.
     */
    public function scopeByBiller($query, $billerCode)
    {
        return $query->where('biller_code', $billerCode);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Mark payment as successful.
     */
    public function markAsSuccessful(?string $flwReference = null): void
    {
        $this->update([
            'status' => 'successful',
            'flw_reference' => $flwReference ?? $this->flw_reference,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed(string $reason = null): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    /**
     * Get display name for the customer.
     */
    public function getCustomerDisplayAttribute(): string
    {
        return $this->customer_name ?: $this->customer_id;
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'successful';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
