<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafehavenUtilitiesProvider extends Model
{
    protected $fillable = [
        'providerCommission',
        '_id',
        'name',
        'amount',
        'identifier',
        'service',
        'vendor',
        'isFixedAmount',
        'description',
        'logoUrl',
    ];
}
