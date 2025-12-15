<?php

// app/Models/Advertisement.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'is_active',
        'display_order',
        'impressions',
        'clicks'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'impressions' => 'integer',
        'clicks' => 'integer'
    ];

    public function incrementImpressions()
    {
        $this->increment('impressions');
    }

    public function incrementClicks()
    {
        $this->increment('clicks');
    }
}
