<?php

namespace Database\Seeders;

use App\Models\SupportedCurrency;
use Illuminate\Database\Seeder;

class SupportedCurrencySeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            [
                'code' => 'NGN',
                'name' => 'Nigerian Naira',
                'symbol' => '₦',
                'type' => 'fiat',
                'is_active' => true,
                'min_redemption' => 100,
                'exchange_rate' => 1.0000,
                'banks' => [
                    'Access Bank', 'Citibank', 'Diamond Bank', 'Ecobank Nigeria',
                    'Fidelity Bank Nigeria', 'First Bank of Nigeria', 'First City Monument Bank',
                    'Guaranty Trust Bank', 'Heritage Bank Plc', 'Keystone Bank Limited',
                    'Polaris Bank', 'Providus Bank Plc', 'Stanbic IBTC Bank Nigeria Limited',
                    'Standard Chartered Bank', 'Sterling Bank Plc', 'SunTrust Bank Nigeria Limited',
                    'Union Bank of Nigeria', 'United Bank for Africa', 'Unity Bank Plc',
                    'Wema Bank Plc', 'Zenith Bank Plc'
                ],
                'instructions' => 'Bank transfers are processed within 24-48 hours. Ensure account details are correct.',
                'sort_order' => 1
            ],
            [
                'code' => 'PHP',
                'name' => 'Philippine Peso',
                'symbol' => '₱',
                'type' => 'fiat',
                'is_active' => true,
                'min_redemption' => 100,
                'exchange_rate' => 1.0000,
                'instructions' => 'Bank transfers are processed within 24-48 hours. Please provide complete bank details.',
                'sort_order' => 2
            ],
            [
                'code' => 'USDT',
                'name' => 'Tether (USDT)',
                'symbol' => '$',
                'type' => 'crypto',
                'is_active' => true,
                'min_redemption' => 100,
                'exchange_rate' => 1.0000,
                'networks' => [
                    'TRC20' => 'Tron (TRC20)',
                    'ERC20' => 'Ethereum (ERC20)',
                    'BEP20' => 'Binance Smart Chain (BEP20)',
                    'POLYGON' => 'Polygon (MATIC)'
                ],
                'instructions' => 'Crypto transfers are irreversible. Double-check your wallet address and network.',
                'sort_order' => 3
            ]
        ];

        foreach ($currencies as $currency) {
            SupportedCurrency::create($currency);
        }
    }
}
