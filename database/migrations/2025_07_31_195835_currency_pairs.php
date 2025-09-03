<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currency_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_currency_id')->constrained('currencies')->onDelete('cascade');
            $table->foreignId('quote_currency_id')->constrained('currencies')->onDelete('cascade');
            $table->decimal('buy_rate', 16, 8)->comment('Rate at which the platform buys the base currency');
            $table->decimal('sell_rate', 16, 8)->comment('Rate at which the platform sells the base currency');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure each currency pair is unique
            $table->unique(['base_currency_id', 'quote_currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_pairs');
    }
};
