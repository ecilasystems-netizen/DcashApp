<?php

namespace Tests\Unit;

use App\Models\CurrencyPair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyPairTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_currency_pair_with_valid_attributes()
    {
//        $baseCurrency = Currency::factory()->create();
//        $quoteCurrency = Currency::factory()->create();

        $currencyPair = CurrencyPair::create([
            'base_currency_id' => 2,
            'quote_currency_id' => 1,
            'buy_rate' => 50.123456,
            'sell_rate' => 49.987654,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('currency_pairs', [
            'id' => $currencyPair->id,
            'buy_rate' => '50.123456',
            'sell_rate' => '49.987654',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_has_correct_relationships()
    {
//        $baseCurrency = Currency::factory()->create();
//        $quoteCurrency = Currency::factory()->create();

        $currencyPair = CurrencyPair::create([
            'base_currency_id' => 2,
            'quote_currency_id' => 1,
            'buy_rate' => 50.123456,
            'sell_rate' => 49.987654,
            'is_active' => true,
        ]);

        $this->assertTrue($currencyPair->baseCurrency->is($baseCurrency));
        $this->assertTrue($currencyPair->quoteCurrency->is($quoteCurrency));
    }
}
