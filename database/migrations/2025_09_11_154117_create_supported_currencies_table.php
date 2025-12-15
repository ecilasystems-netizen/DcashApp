<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supported_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // NGN, PHP, USDT
            $table->string('name'); // Nigerian Naira
            $table->string('symbol', 5); // ₦, ₱, $
            $table->enum('type', ['fiat', 'crypto'])->default('fiat');
            $table->boolean('is_active')->default(true);
            $table->integer('min_redemption')->default(100); // Minimum redemption amount
            $table->json('networks')->nullable(); // For crypto currencies
            $table->json('banks')->nullable(); // For fiat currencies with predefined banks
            $table->decimal('exchange_rate', 10, 4)->default(1.0000); // 1 DCoin = X currency
            $table->text('instructions')->nullable(); // Special instructions for this currency
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supported_currencies');
    }
};
