<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., Nigerian Naira, Tether
            $table->string('code', 10)->unique(); // e.g., NGN, USDT
            $table->enum('type', ['fiat', 'crypto'])->default('fiat');
            $table->string('symbol', 10)->nullable(); // e.g., $, €, ₦
            $table->string('flag', 100)->nullable(); // URL to the currency flag image
            $table->boolean('is_wallet_supported')->default(false); // Indicates if currency is supported in wallet
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
