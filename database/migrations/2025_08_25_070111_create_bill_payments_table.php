<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // If you have user authentication
            $table->string('reference')->unique();
            $table->string('flw_reference')->nullable(); // Flutterwave's internal reference
            $table->string('biller_code');
            $table->string('biller_name')->nullable();
            $table->string('item_code')->nullable();
            $table->string('category');
            $table->string('customer_id'); // meter number, phone number, etc.
            $table->string('customer_name')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('NGN');
            $table->string('country', 2);
            $table->enum('status', ['pending', 'successful', 'failed', 'cancelled'])->default('pending');
            $table->string('type'); // AIRTIME, DSTV, GOTV, etc.
            $table->text('description')->nullable();
            $table->string('recurrence')->nullable(); // ONCE, DAILY, WEEKLY, MONTHLY, QUARTERLY
            $table->decimal('fee', 8, 2)->nullable();
            $table->json('flw_response')->nullable(); // Store full Flutterwave response
            $table->string('callback_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            // Add indexes for better query performance
            $table->index(['user_id', 'status']);
            $table->index(['reference']);
            $table->index(['biller_code', 'status']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_payments');
    }
};
