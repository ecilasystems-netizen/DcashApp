<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafehavenAirtimeProvider extends Model
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
