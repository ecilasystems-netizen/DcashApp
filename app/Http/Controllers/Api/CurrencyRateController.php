<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyPair;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function getAllCurrencies()
    {
        $currencies = Currency::where('status', 1)
            ->select('id', 'code', 'name', 'symbol', 'flag')
            ->orderBy('code')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $currencies
        ]);
    }

    public function getAllRates()
    {
        $pairs = CurrencyPair::with(['baseCurrency:id,code,name,symbol', 'quoteCurrency:id,code,name,symbol'])
            ->where('is_active', true)
            ->whereNotNull('raw_rate')
            ->where('raw_rate', '>', 0)
            ->select('id', 'base_currency_id', 'quote_currency_id', 'rate', 'raw_rate', 'updated_at')
            ->get()
            ->map(function ($pair) {
                return [
                    'pair' => $pair->baseCurrency->code.'/'.$pair->quoteCurrency->code,
                    'base_currency' => $pair->baseCurrency->code,
                    'quote_currency' => $pair->quoteCurrency->code,
                    'rate' => (float) $pair->rate ?: 0,
                    'raw_rate' => (float) $pair->raw_rate ?: 0,
                    'last_updated' => $pair->updated_at->toISOString()
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pairs,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function getRate(Request $request)
    {
        $baseCurrency = $request->input('base');
        $quoteCurrency = $request->input('quote');

        if (!$baseCurrency || !$quoteCurrency) {
            return response()->json([
                'success' => false,
                'message' => 'Both base and quote currencies are required'
            ], 400);
        }

        $baseCurrencyModel = Currency::where('code', strtoupper($baseCurrency))->first();
        $quoteCurrencyModel = Currency::where('code', strtoupper($quoteCurrency))->first();

        if (!$baseCurrencyModel || !$quoteCurrencyModel) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid currency code'
            ], 400);
        }

        $pair = CurrencyPair::where('base_currency_id', $baseCurrencyModel->id)
            ->where('quote_currency_id', $quoteCurrencyModel->id)
            ->where('is_active', true)
            ->whereNotNull('raw_rate')
            ->where('raw_rate', '>', 0)
            ->first();

        if (!$pair) {
            return response()->json([
                'success' => false,
                'message' => 'Currency pair not found or rate not available'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'pair' => $baseCurrency.'/'.$quoteCurrency,
                'base_currency' => $baseCurrencyModel->code,
                'quote_currency' => $quoteCurrencyModel->code,
                'rate' => (float) $pair->rate ?: 0,
                'raw_rate' => (float) $pair->raw_rate ?: 0,
                'last_updated' => $pair->updated_at->toISOString()
            ]
        ]);
    }

    public function convertAmount(Request $request)
    {
        $baseCurrency = $request->input('base');
        $quoteCurrency = $request->input('quote');
        $amount = $request->input('amount');

        if (!$baseCurrency || !$quoteCurrency || !$amount) {
            return response()->json([
                'success' => false,
                'message' => 'Base currency, quote currency, and amount are required'
            ], 400);
        }

        if (!is_numeric($amount) || $amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Amount must be a positive number'
            ], 400);
        }

        $baseCurrencyModel = Currency::where('code', strtoupper($baseCurrency))->first();
        $quoteCurrencyModel = Currency::where('code', strtoupper($quoteCurrency))->first();

        if (!$baseCurrencyModel || !$quoteCurrencyModel) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid currency code'
            ], 400);
        }

        $pair = CurrencyPair::where('base_currency_id', $baseCurrencyModel->id)
            ->where('quote_currency_id', $quoteCurrencyModel->id)
            ->where('is_active', true)
            ->whereNotNull('raw_rate')
            ->where('raw_rate', '>', 0)
            ->first();

        if (!$pair) {
            return response()->json([
                'success' => false,
                'message' => 'Currency pair not found or rate not available'
            ], 404);
        }

        // Ensure we have valid numeric values
        $rawRate = (float) $pair->raw_rate;
        $inputAmount = (float) $amount;

        if ($rawRate <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid exchange rate'
            ], 400);
        }

        $convertedAmount = $inputAmount * $rawRate;

        return response()->json([
            'success' => true,
            'data' => [
                'pair' => $baseCurrency.'/'.$quoteCurrency,
                'base_currency' => $baseCurrencyModel->code,
                'quote_currency' => $quoteCurrencyModel->code,
                'base_amount' => $inputAmount,
                'quote_amount' => round($convertedAmount, 2),
                'rate' => (float) $pair->rate ?: 0,
                'raw_rate' => $rawRate,
                'last_updated' => $pair->updated_at->toISOString()
            ]
        ]);
    }
}
