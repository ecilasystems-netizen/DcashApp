<?php

namespace App\Livewire\Admin\Rewards;

use App\Models\SupportedCurrency;
use Livewire\Component;

class DcoinRates extends Component
{
    public $currencies = [];
    public $search = '';
    public $typeFilter = '';

    protected $rules = [
        'currencies.*.exchange_rate' => 'required|numeric|min:0',
        'currencies.*.min_redemption' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->loadCurrencies();
    }

    public function loadCurrencies()
    {
        $query = SupportedCurrency::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            })
            ->when($this->typeFilter, function ($q) {
                $q->where('type', $this->typeFilter);
            })
            ->ordered();

        $this->currencies = $query->get()->map(function ($currency) {
            return [
                'id' => $currency->id,
                'code' => $currency->code,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'type' => $currency->type,
                'is_active' => $currency->is_active,
                'exchange_rate' => $currency->exchange_rate,
                'min_redemption' => $currency->min_redemption,
                'sort_order' => $currency->sort_order,
            ];
        })->toArray();
    }

    public function updatedSearch()
    {
        $this->loadCurrencies();
    }

    public function updatedTypeFilter()
    {
        $this->loadCurrencies();
    }

    public function toggleStatus($index)
    {
        $currency = SupportedCurrency::find($this->currencies[$index]['id']);
        if ($currency) {
            $currency->update(['is_active' => !$currency->is_active]);
            $this->currencies[$index]['is_active'] = $currency->is_active;

            session()->flash('message', 'Currency status updated successfully.');
        }
    }

    public function updateRates()
    {
        $this->validate();

        foreach ($this->currencies as $currencyData) {
            SupportedCurrency::where('id', $currencyData['id'])->update([
                'exchange_rate' => $currencyData['exchange_rate'],
                'min_redemption' => $currencyData['min_redemption'],
            ]);
        }

        session()->flash('message', 'Exchange rates updated successfully.');
    }

    public function updateSingleRate($index)
    {
        $this->validate([
            "currencies.$index.exchange_rate" => 'required|numeric|min:0',
            "currencies.$index.min_redemption" => 'required|integer|min:1',
        ]);

        $currency = SupportedCurrency::find($this->currencies[$index]['id']);
        if ($currency) {
            $currency->update([
                'exchange_rate' => $this->currencies[$index]['exchange_rate'],
                'min_redemption' => $this->currencies[$index]['min_redemption'],
            ]);

            session()->flash('message', "Rate for {$currency->name} updated successfully.");
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->typeFilter = '';
        $this->loadCurrencies();
    }

    public function render()
    {
        return view('livewire.admin.rewards.dcoin-rates')->layout('layouts.admin.app', [
            'title' => 'Dcoin Exchange Rates',
            'description' => 'Manage exchange rates for supported currencies',
        ]);
    }
}
