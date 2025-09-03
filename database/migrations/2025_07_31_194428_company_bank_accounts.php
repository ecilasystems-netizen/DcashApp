<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->enum('account_type', ['mobile_wallet', 'bank', 'gcash', 'crypto_wallet'])->default('bank')->comment('type of account');
            $table->string('bank_account_qr_code')->nullable()->comment('QR code for non-crypto payments');
            $table->boolean('is_crypto')->default(false);
            $table->string('crypto_wallet_address')->nullable();
            $table->string('crypto_name')->nullable();
            $table->string('crypto_network')->nullable();
            $table->string('crypto_qr_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_bank_accounts');
    }
};
