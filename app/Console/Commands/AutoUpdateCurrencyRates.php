<?php

namespace App\Console\Commands;

use App\Jobs\UpdateCurrencyRatesJob;
use Illuminate\Console\Command;

class AutoUpdateCurrencyRates extends Command
{

    protected $signature = 'currency:update';
    protected $description = 'Update currency exchange rates';

    public function handle()
    {
        $this->info('Dispatching currency rates update job...');
        UpdateCurrencyRatesJob::dispatch();
        $this->info('Job dispatched successfully!');
    }
}
