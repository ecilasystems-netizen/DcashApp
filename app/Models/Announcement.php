<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content_type',
        'content',
        'cta_text',
        'cta_link',
        'views',
        'clicks',
        'is_active',
        'starts_at',
        'ends_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'array', // Automatically handle JSON encoding/decoding
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the performance as a Click-Through Rate (CTR).
     * This is a calculated property and not stored in the database.
     */
    protected function clickThroughRate(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->views === 0) {
                    return 0;
                }
                return round(($this->clicks / $this->views) * 100, 2);
            }
        );
    }
}
