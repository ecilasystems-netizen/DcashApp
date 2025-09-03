<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlutterwaveBillsItem extends Model
{
    protected $fillable = [
        'biller_code',
        'name',
        'default_commission',
        'date_added',
        'country',
        'is_airtime',
        'biller_name',
        'item_code',
        'short_name',
        'fee',
        'commission_on_fee',
        'reg_expression',
        'label_name',
        'amount',
        'is_resolvable',
        'group_name',
        'category_name',
        'is_data',
        'default_commission_on_amount',
        'commission_on_fee_or_amount',
        'validity_period',
    ];

    // In your FlutterwaveBillsItem model
    protected $casts = [
        'is_data' => 'boolean',
        'is_airtime' => 'boolean',
        'is_resolvable' => 'boolean',
        'commission_on_fee' => 'boolean',
        'commission_on_fee_or_amount' => 'boolean'
    ];
}
