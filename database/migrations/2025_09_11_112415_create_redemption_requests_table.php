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
        Schema::create('redemption_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency', 10); // NGN, PHP, USDT
            $table->decimal('amount', 15, 2); // DCoins amount to redeem
            $table->decimal('equivalent_amount', 12, 2)->nullable()->after('amount');
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('equivalent_amount');
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->string('reference')->unique(); // RDM-XXXXX
            $table->json('bank_details')->nullable(); // For NGN/PHP - account name, number, bank
            $table->json('wallet_details')->nullable(); // For USDT - address, network
            $table->timestamp('processed_at')->nullable();
            $table->text('notes')->nullable(); // Admin notes for rejection/processing
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index('reference');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redemption_requests');
    }
};
