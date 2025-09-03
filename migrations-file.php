<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    public function up(): void
    {
        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Cache table
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        // Jobs table
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // Currencies table
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name');
            $table->string('symbol');
            $table->timestamps();
        });

        // Exchange transactions table
        Schema::create('exchange_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('from_currency_id')->constrained('currencies')->onDelete('restrict');
            $table->foreignId('to_currency_id')->constrained('currencies')->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->decimal('rate', 10, 6);
            $table->decimal('converted_amount', 15, 2);
            $table->timestamps();
        });

        // Wallets table
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('currency_id')->constrained()->onDelete('restrict');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // Wallet transactions table
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('restrict');
            $table->string('type'); // credit/debit
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->timestamps();
        });

        // Beneficiaries table
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('swift_code')->nullable();
            $table->timestamps();
        });

        // Company bank accounts table
        Schema::create('company_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained()->onDelete('restrict');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('swift_code');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Virtual bank accounts table
        Schema::create('virtual_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('currency_id')->constrained()->onDelete('restrict');
            $table->string('account_number')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Currency pairs table
        Schema::create('currency_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_currency_id')->constrained('currencies')->onDelete('restrict');
            $table->foreignId('quote_currency_id')->constrained('currencies')->onDelete('restrict');
            $table->decimal('rate', 10, 6);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency_pairs');
        Schema::dropIfExists('virtual_bank_accounts');
        Schema::dropIfExists('company_bank_accounts');
        Schema::dropIfExists('beneficiaries');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('exchange_transactions');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('users');
    }
}
