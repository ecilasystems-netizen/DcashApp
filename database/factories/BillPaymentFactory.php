<?php

namespace Database\Factories;

use App\Models\BillPayment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BillPaymentFactory extends Factory
{
    protected $model = BillPayment::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
