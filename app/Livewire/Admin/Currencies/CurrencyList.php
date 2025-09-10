<?php

namespace App\Livewire\Admin\Currencies;

use App\Models\Currency;
use App\Models\CurrencyPair;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class CurrencyList extends Component
{
    use WithFileUploads;

    public $name, $code, $symbol, $is_crypto = false, $flag;
    public $potentialPairs = [];
    public $newPairRates = [];

    public $selectedRateId;
    public $newBuyRate;
    public $newSellRate;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:10',
        'symbol' => 'required|string|max:10',
        'flag' => 'nullable|image|max:1024', // 1MB max
        'newBuyRate' => 'required|numeric|min:0',
        'newSellRate' => 'required|numeric|min:0',
    ];

    protected $rateRules = [
        'selectedRateId' => 'required|exists:currency_pairs,id',
        'newBuyRate' => 'required|numeric|min:0',
        'newSellRate' => 'required|numeric|min:0',
    ];

    public function updateRate()
    {
        $this->validate($this->rateRules);

        try {
            $rate = CurrencyPair::findOrFail($this->selectedRateId);
            $rate->update([
                'rate' => $this->newBuyRate,
                'raw_rate' => $this->newBuyRate
            ]);

            $reverseRate = CurrencyPair::where('base_currency_id', $rate->quote_currency_id)
                ->where('quote_currency_id', $rate->base_currency_id)
                ->first();

            if ($reverseRate) {
                $reverseRate->update([
                    'rate' => $this->newSellRate,
                    'raw_rate' => 1 / $this->newSellRate
                ]);
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Rate updated successfully!'
            ]);


            // Add this line to close the modal
            $this->dispatch('close-rate-modal');
            
            // Reset form fields
            $this->reset(['selectedRateId', 'newBuyRate', 'newSellRate']);


        } catch (\Exception $e) {
            report($e);
            Log::error('Failed to update rate: '.$e->getMessage());

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to update rate.'
            ]);
        }
    }

    public function updatedName()
    {
        // This method will trigger whenever the currency name changes
        if (!empty($this->name) && !empty($this->code)) {
            $this->generatePotentialPairs();
        }
    }

    public function generatePotentialPairs()
    {
        // Get existing currencies
        $existingCurrencies = Currency::where('status', 1)->get();

        $this->potentialPairs = [];

        foreach ($existingCurrencies as $currency) {
            // Create two pairs: new->existing and existing->new
            $newToExistingKey = "new_to_{$currency->id}";
            $existingToNewKey = "{$currency->id}_to_new";

            // Add to potential pairs array
            $this->potentialPairs[] = [
                'key' => $newToExistingKey,
                'pair' => "{$this->code} → {$currency->code}",
                'direction' => 'buy',
                'existing_currency' => $currency
            ];

            $this->potentialPairs[] = [
                'key' => $existingToNewKey,
                'pair' => "{$currency->code} → {$this->code}",
                'direction' => 'sell',
                'existing_currency' => $currency
            ];

            // Initialize rate fields
            $this->newPairRates[$newToExistingKey] = '';
            $this->newPairRates[$existingToNewKey] = '';
        }
    }

    public function updatedCode()
    {
        // Also trigger when code changes
        if (!empty($this->name) && !empty($this->code)) {
            $this->generatePotentialPairs();
        }
    }

    public function saveCurrency()
    {
        $this->validate();

        $flagPath = $this->flag ? $this->flag->store('images/flags', 'public') : null;

        // Create the new currency
        $newCurrency = Currency::create([
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'type' => $this->is_crypto ? 'crypto' : 'fiat',
            'flag' => $flagPath,
            'status' => 1,
        ]);

        // Save the currency pairs
        foreach ($this->potentialPairs as $pair) {
            $key = $pair['key'];
            $rate = $this->newPairRates[$key] ?? null;

            if (!empty($rate)) {
                if (str_starts_with($key, 'new_to_')) {
                    // New currency is base, existing is quote
                    $baseId = $newCurrency->id;
                    $quoteId = $pair['existing_currency']->id;
                } else {
                    // Existing currency is base, new is quote
                    $baseId = $pair['existing_currency']->id;
                    $quoteId = $newCurrency->id;
                }

                CurrencyPair::create([
                    'base_currency_id' => $baseId,
                    'quote_currency_id' => $quoteId,
                    'rate' => $rate,
                    'raw_rate' => $rate,
                    'is_active' => 1
                ]);
            }
        }

        // Reset form fields
        $this->reset(['name', 'code', 'symbol', 'is_crypto', 'flag', 'potentialPairs', 'newPairRates']);

        // Close modal
        $this->dispatch('close-currency-modal');
    }

    public function render()
    {

        $currencies = Currency::where('status', 1)->get();

        // Get all active currency pairs
        $rawPairs = CurrencyPair::where('is_active', 1)->get();

        // Create a collection to hold unique currency pairs with buy/sell rates
        $uniquePairs = collect();
        $processedPairs = [];

        // Process each pair to organize them
        foreach ($rawPairs as $pair) {
            $baseCurrency = $pair->baseCurrency->code;
            $quoteCurrency = $pair->quoteCurrency->code;

            // Create a unique key for this currency combination
            // Sort the currencies alphabetically to ensure consistency
            $currencyCombo = [$baseCurrency, $quoteCurrency];
            sort($currencyCombo);
            $uniqueKey = implode('-', $currencyCombo);

            // Skip if we've already processed this currency pair
            if (in_array($uniqueKey, $processedPairs)) {
                continue;
            }

            // Find the pair in both directions
            $directPair = $rawPairs->first(function ($item) use ($baseCurrency, $quoteCurrency) {
                return $item->baseCurrency->code === $baseCurrency &&
                    $item->quoteCurrency->code === $quoteCurrency;
            });

            $reversePair = $rawPairs->first(function ($item) use ($baseCurrency, $quoteCurrency) {
                return $item->baseCurrency->code === $quoteCurrency &&
                    $item->quoteCurrency->code === $baseCurrency;
            });

            // Format display name (always show in alphabetical order for consistency)
            if ($baseCurrency < $quoteCurrency) {
                $pairName = "$baseCurrency → $quoteCurrency";
            } else {
                $pairName = "$quoteCurrency → $baseCurrency";
            }

            // Determine buy and sell rates based on available pairs
            $buyRate = $directPair ? $directPair->rate : null;
            $sellRate = $reversePair ? $reversePair->rate : null;

            $uniquePairs->push([
                'id' => $directPair ? $directPair->id : ($reversePair ? $reversePair->id : null),
                'pair' => $pairName,
                'buy_rate' => $buyRate,
                'sell_rate' => $sellRate,
                'base_currency' => $baseCurrency,
                'quote_currency' => $quoteCurrency,
                'direct_pair_id' => $directPair ? $directPair->id : null,
                'reverse_pair_id' => $reversePair ? $reversePair->id : null
            ]);

            // Mark this pair as processed
            $processedPairs[] = $uniqueKey;
        }

        $currencyPairs = $uniquePairs;

        return view('livewire.admin.currencies.currency-list', [
            'currencies' => $currencies, 'currencyPairs' => $currencyPairs
        ])->layout('layouts.admin.app', [
            'title' => 'Currencies',
            'description' => 'List of all currencies',
        ]);
    }
}
