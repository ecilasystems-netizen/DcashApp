<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountTier extends Model
{
    public $fillable = ['name', 'max_daily_transaction', 'max_daily_balance'];
}
