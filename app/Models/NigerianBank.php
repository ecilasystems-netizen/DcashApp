<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NigerianBank extends Model
{
    public $timestamps = false;
    protected $table = 'nigerian_banks';
    protected $fillable = [
        'name',
        'code',
        'slug',
        'ussd',
        'logo',
    ];
}
