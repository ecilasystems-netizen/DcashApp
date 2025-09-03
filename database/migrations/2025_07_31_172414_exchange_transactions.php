<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('company_bank_account_id')->constrained('company_bank_accounts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_currency_id')->constrained('currencies')->onDelete('cascade');
            $table->foreignId('to_currency_id')->constrained('currencies')->onDelete('cascade');
            $table->decimal('amount_from', 24, 8);
            $table->decimal('amount_to', 24, 8);
            $table->decimal('rate', 24, 8);

            // -- Recipient Details (for where the user receives funds) --

            // For Fiat (e.g., receiving NGN into a bank account)
            $table->string('recipient_bank_name')->nullable();
            $table->string('recipient_account_number')->nullable();
            $table->string('recipient_account_name')->nullable();

            // For Crypto (e.g., receiving USDT into a wallet)
            $table->string('recipient_wallet_address')->nullable()->comment('User\'s sending crypto wallet address for verification');
            $table->string('recipient_network')->nullable()->comment('e.g., TRC20, ERC20, BEP20');

            // -- Payment Proof (for how the user pays for the exchange) --

            // For when user sends crypto (e.g., their USDT payment)
            $table->string('payment_transaction_hash')->nullable()->comment('Transaction hash/ID of the user\'s crypto payment');
            // For when user sends fiat (e.g., their NGN bank transfer)
            $table->string('payment_proof')->nullable()->comment('Path to the uploaded payment receipt file');

            $table->enum('status', ['pending_payment', 'pending_confirmation', 'processing', 'completed', 'failed', 'cancelled'])->default('pending_payment');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_transactions');
    }
};
