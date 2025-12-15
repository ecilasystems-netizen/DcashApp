<?php

use App\Http\Controllers\Api\Flutterwave\FlutterwaveBillsController;
use App\Http\Controllers\FlutterwaveWebhookController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Alternative routes for direct controller-to-controller usage (no middleware)
Route::prefix('v1/bills')->name('bills.internal.')->group(function () {
    Route::get('/billers', [FlutterwaveBillsController::class, 'getBillers'])->name('billers');
    Route::get('/items/{billerCode}', [FlutterwaveBillsController::class, 'getBillerItems'])->name('items');
    Route::post('/validate-customer', [FlutterwaveBillsController::class, 'validateCustomerDetails'])->name('validate');
    Route::post('/pay', [FlutterwaveBillsController::class, 'createBillPayment'])->name('pay');
    Route::get('/status/{reference}', [FlutterwaveBillsController::class, 'getBillPaymentStatus'])->name('status');
});

Route::get('/bills/import',
    [FlutterwaveBillsController::class, 'importFromJson'])->name('bills-flutterwave.import-from-json');


Route::get('/test/create-safe-haven-sub-account', [TestController::class, 'createSafeHavenSubAccount']);

//flutterwave webhook route
Route::post('/webhooks/flutterwave',
    [FlutterwaveWebhookController::class, 'handleFlutterwaveWebhook'])->name('webhooks.flutterwave');


// Safe Haven API Test Routes
Route::get('/safe-haven/bank-list', [TestController::class, 'getBanks']);
Route::get('/safe-haven/bank-list/sync', [TestController::class, 'syncSafeHavenBankList']);
Route::post('/safe-haven/verify-account', [TestController::class, 'verifyAccountDetails']);

Route::get('/safe-haven/test-route', [TestController::class, 'safeHaveApiTestRoute']);

// Currency Rate API Routes (no authentication required)
Route::prefix('currency')->group(function () {
    Route::get('/currencies', [App\Http\Controllers\Api\CurrencyRateController::class, 'getAllCurrencies']);
    Route::get('/rates', [App\Http\Controllers\Api\CurrencyRateController::class, 'getAllRates']);
    Route::get('/rate', [App\Http\Controllers\Api\CurrencyRateController::class, 'getRate']);
    Route::get('/convert', [App\Http\Controllers\Api\CurrencyRateController::class, 'convertAmount']);
});
