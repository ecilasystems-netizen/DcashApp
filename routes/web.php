<?php

use App\Http\Controllers\Api\Flutterwave\FlutterwaveBillsController;
use App\Http\Controllers\Api\Flutterwave\UtilityBillsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\TestController;
use App\Livewire\Admin\Accounts\IndexLimitUpgradeRequest;
use App\Livewire\Admin\Advertisements\AdList;
use App\Livewire\Admin\Agents\IndexAgents;
use App\Livewire\Admin\Announcements\AnnouncementList;
use App\Livewire\Admin\Announcements\CreateAnnouncement;
use App\Livewire\Admin\BankAccounts\BankAccountList;
use App\Livewire\Admin\Currencies\CurrencyList;
use App\Livewire\Admin\Dashboard\Index;
use App\Livewire\Admin\Kyc\KycList;
use App\Livewire\Admin\Rewards\BonusManagement;
use App\Livewire\Admin\Rewards\DcoinRates;
use App\Livewire\Admin\Rewards\UserRedeemBonus;
use App\Livewire\Admin\Transactions\IndexWalletTransactions;
use App\Livewire\Admin\Transactions\ShowWalletTransaction;
use App\Livewire\Admin\Transactions\TransactionList;
use App\Livewire\Admin\Users\EditUser;
use App\Livewire\Admin\Users\UserList;
use App\Livewire\Admin\Users\WalletUsers;
use App\Livewire\Agent\AgentDashboard;
use App\Livewire\Agent\AgentTransactions;
use App\Livewire\App\Auth\Login;
use App\Livewire\App\Auth\NewPassword;
use App\Livewire\App\Auth\PasswordResetSuccessful;
use App\Livewire\App\Auth\Register;
use App\Livewire\App\Auth\RegisterOtp;
use App\Livewire\App\Auth\ResetPassword;
use App\Livewire\App\Auth\SuccessPage;
use App\Livewire\App\Auth\VerifyOtp;
use App\Livewire\App\Exchange\Dashboard;
use App\Livewire\App\Exchange\ExchangeBankAccount;
use App\Livewire\App\Exchange\ExchangeCompleted;
use App\Livewire\App\Exchange\ExchangeReceipt;
use App\Livewire\App\Exchange\PaymentPage;
use App\Livewire\App\Exchange\Profile;
use App\Livewire\App\Exchange\Transactions;
use App\Livewire\App\Kyc\KycStart;
use App\Livewire\App\Kyc\PersonalInfo;
use App\Livewire\App\Kyc\Selfie;
use App\Livewire\App\Kyc\UnderReview;
use App\Livewire\App\Kyc\UploadDocs;
use App\Livewire\App\Wallet\Airtime\IndexAirtime;
use App\Livewire\App\Wallet\BuyPower\CreateBuyPower;
use App\Livewire\App\Wallet\CableTv\CreateCableTv;
use App\Livewire\App\Wallet\Deposits\CreateDeposit;
use App\Livewire\App\Wallet\MobileData\IndexMobileData;
use App\Livewire\App\Wallet\TermsConditions\IndexTermsConditions;
use App\Livewire\App\Wallet\Transactions\Receipt;
use App\Livewire\App\Wallet\Transfers\CreateTransfer;
use App\Livewire\Rewards\IndexCashbacks;
use App\Livewire\Rewards\IndexReferrals;
use App\Livewire\Rewards\IndexRewards;
use App\Livewire\Rewards\RedeemBonus;
use App\Livewire\Rewards\ViewBonusTransaction;
use App\Livewire\Test\CreateSafeHavenSubAccount;
use Illuminate\Support\Facades\Route;

//admin authentication routes
Route::get('/admin/login', \App\Livewire\Admin\Auth\Login::class)->name('admin.login');


//agent routes
Route::middleware('agentGroup')->group(function () {
    Route::get('/agent/dashboard', AgentDashboard::class)->name('agent.dashboard');
    Route::get('/agent/transactions', AgentTransactions::class)->name('agent.transactions');
    Route::get('/agent/currencies', CurrencyList::class)->name('agent.currencies');

});

//admin routes
Route::middleware('adminGroup')->group(function () {

    Route::get('/admin/dashboard', Index::class)->name('admin.dashboard');

    Route::get('/admin/users', UserList::class)->name('admin.users');
    Route::get('/admin/users/{user}/edit', EditUser::class)->name('admin.users.edit');
    Route::get('/admin/users/wallet-users', WalletUsers::class)->name('admin.users.wallet-users');

    Route::get('/admin/transactions', TransactionList::class)->name('admin.transactions');
    Route::get('/admin/wallet-transactions', IndexWalletTransactions::class)->name('admin.wallet-transactions');
    Route::get('/admin/wallet-transactions/{transaction}', ShowWalletTransaction::class)
        ->name('admin.wallet-transactions.show');
    Route::get('/admin/kyc', KycList::class)->name('admin.kyc');
    Route::get('/admin/currencies', CurrencyList::class)->name('admin.currencies');
    Route::get('/admin/announcements', AnnouncementList::class)->name('admin.announcements');
    Route::get('/admin/announcements/create', CreateAnnouncement::class)->name('admin.announcements.create');
    Route::get('/admin/bank-accounts', BankAccountList::class)->name('admin.bank-accounts');

    Route::get('/admin/rewards/redeem-bonus', UserRedeemBonus::class)->name('admin.redeem-bonus');

    Route::get('/admin/limit-requests', IndexLimitUpgradeRequest::class)->name('admin.limit-requests');

    Route::get('/admin/agents', IndexAgents::class)->name('admin.agents');

    Route::get('/admin/rewards/dcoin-rates', DcoinRates::class)->name('admin.dcoin-rates');

    Route::get('/admin/bonus-management', BonusManagement::class)->name('admin.bonus-management');

});

//ROUTE WALLET TRANSACTIONS
Route::get('/wallet/transactions/receipt/{ref}', Receipt::class)->name('wallet.transactions.receipt');

//REWARDS
Route::get('/rewards/transactions/receipt/{ref}', ViewBonusTransaction::class)->name('rewards.transactions.receipt');

//USer Authentication Routes
Route::get('/login', Login::class)->name('login');
Route::get('/reset-password', ResetPassword::class)->name('reset-password');
Route::get('/reset-password/verify-otp', VerifyOtp::class)->name('reset-password.verify-otp');
Route::get('/reset-password/new-password', NewPassword::class)->name('reset-password.new-password');
Route::get('/reset-password/success', PasswordResetSuccessful::class)->name('reset-password.success');
Route::post('/logout', AuthenticatedSessionController::class.'@destroy')->name('logout');

Route::get('/register', Register::class)->name('register');
Route::get('/register/otp/{email}', RegisterOtp::class)->name('register.otp');
Route::get('/success', SuccessPage::class)->name('success');

Route::middleware('authGroup')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard')->middleware('auth.mustLogin');

    //exchange
    Route::get('/exchange/enter-bank-account', ExchangeBankAccount::class)->name('exchange.enter-bank-account');
    Route::get('/exchange/payment', PaymentPage::class)->name('exchange.payment');
    Route::get('/exchange/completed/{ref}', ExchangeCompleted::class)->name('exchange.completed');
    Route::get('/exchange/receipt/{ref}', ExchangeReceipt::class)->name('exchange.receipt');
    Route::get('/exchange/transactions', Transactions::class)->name('exchange.transactions');

    Route::get('/profile', Profile::class)->name('profile');

    //rewards
    Route::get('/rewards', IndexRewards::class)->name('rewards');
    Route::get('/rewards/referrals', IndexReferrals::class)->name('rewards.referrals');
    Route::get('/rewards/cashbacks', IndexCashbacks::class)->name('rewards.cashbacks');
    Route::get('/rewards/redeem', RedeemBonus::class)->name('rewards.redeem');

    //KYC
    Route::get('/kyc/start', KycStart::class)->name('kyc.start');
    Route::get('/kyc/personal-info', PersonalInfo::class)->name('kyc.personal-info');
    Route::get('/kyc/upload-documents', UploadDocs::class)->name('kyc.upload-documents');
    Route::get('/kyc/selfie', Selfie::class)->name('kyc.selfie');
    Route::get('/kyc/under-review', UnderReview::class)->name('kyc.under-review');
    Route::get('/kyc/under-review/{status}', UnderReview::class)->name('kyc.under-review.status');

    //terms and conditions
    Route::get('/wallet/terms-and-conditions', IndexTermsConditions::class)->name('wallet.terms-and-conditions');

    //airtime route
    Route::get('/wallet/airtime', IndexAirtime::class)->name('wallet.airtime');

    //mobile data route
    Route::get('/wallet/mobile-data', IndexMobileData::class)->name('wallet.mobile-data');

    //make transfer
    Route::get('/wallet/transfers/create', CreateTransfer::class)->name('wallet.transfers.create');

    //deposit fund
    Route::get('/wallet/deposits', CreateDeposit::class)->name('wallet.deposit.create');

    //buy power
    Route::get('/wallet/buy-power', CreateBuyPower::class)->name('wallet.buy-power');

    //pay cable tv
    Route::get('/wallet/cable-tv', CreateCableTv::class)->name('wallet.cable-tv');
});


Route::prefix('bills')->name('bills.')->middleware(['auth'])->group(function () {

    // Bills dashboard
    Route::get('/top-categories', [FlutterwaveBillsController::class, 'getTopBillCategories'])->name('top-categories');
    Route::get('/billers', [FlutterwaveBillsController::class, 'getBillers'])->name('billers');
    Route::get('/biller/items', [FlutterwaveBillsController::class, 'getBillerItems'])->name('billerItems');

});

Route::get('/admin/advertisements/adlist', AdList::class)->name('admin.advertisements.list');

Route::get('/ad/click/{ad}', function (\App\Models\Advertisement $ad) {
    $ad->incrementClicks();
    return redirect($ad->link_url);
})->name('ad.click');

//SAFEHAVEN SUB-ACCOUNT TEST ROUTE

Route::post('/initiate-bvn-verification',
    [TestController::class, 'initiateBvnVerification'])->name('initiate-bvn-verification');
Route::post('/validate-bvn-otp', [TestController::class, 'validateBvnOtp'])->name('validate-bvn-otp');
Route::post('/create-safehaven-subaccount',
    [TestController::class, 'createSafeHavenSubAccount'])->name('create-safehaven-subaccount');

Route::get('/safehaven', CreateSafeHavenSubAccount::class)->name('test-safehaven-account');


//test route
Route::get('/test', function () {
    return view('test.php-info');
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('event:clear');
    return 'Cache Cleared Successfully';
});

Route::get('/cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    Artisan::call('event:cache');
    return 'All Cache Operations Completed Successfully';
});


