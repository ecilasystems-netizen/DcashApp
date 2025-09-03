<?php

use App\Http\Controllers\Api\Flutterwave\FlutterwaveBillsController;
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


Route::get('/test/nigerian-banks', [TestController::class, 'index']);
