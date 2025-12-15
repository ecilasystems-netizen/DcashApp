<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referral_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade'); // User who referred
            $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade'); // User who was referred
            $table->decimal('bonus_amount', 15, 2)->default(100.00); // DCoins earned
            $table->enum('status', ['pending', 'credited', 'revoked'])->default('pending');
            $table->string('trigger_event')->default('registration'); // registration, first_trade, etc.
            $table->timestamp('credited_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['referrer_id', 'status']);
            $table->index('referred_user_id');
            $table->index('created_at');

            // Prevent duplicate bonuses for the same referral
            $table->unique(['referrer_id', 'referred_user_id', 'trigger_event'], 'referral_bonus_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_bonuses');
    }
};
