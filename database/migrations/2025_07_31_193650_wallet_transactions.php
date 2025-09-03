<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'airtime', 'data', 'electricity', 'transfer', 'withdrawal']);
            $table->decimal('amount', 16, 4)->comment('The principal amount of the transaction');
            $table->decimal('charge', 16, 4)->default(0.00);
            $table->string('description');
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->json('metadata')->nullable()->comment('To store extra details like phone number, meter number, recipient account etc.');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
