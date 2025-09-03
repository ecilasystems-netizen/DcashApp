<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilityBillsTable extends Migration
{
    public function up()
    {
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id();
            $table->string('biller_code')->nullable();
            $table->string('name')->nullable();
            $table->decimal('default_commission', 12, 2)->nullable();
            $table->string('date_added')->nullable();
            $table->string('country', 10)->nullable();
            $table->boolean('is_airtime')->nullable();
            $table->string('biller_name')->nullable();
            $table->string('item_code')->nullable();
            $table->string('short_name')->nullable();
            $table->decimal('fee', 12, 2)->nullable();
            $table->decimal('commission_on_fee', 12, 2)->nullable();
            $table->string('reg_expression')->nullable();
            $table->string('label_name')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->boolean('is_resolvable')->nullable();
            $table->string('group_name')->nullable();
            $table->string('category_name')->nullable();
            $table->boolean('is_data')->nullable();
            $table->decimal('default_commission_on_amount', 12, 2)->nullable();
            $table->decimal('commission_on_fee_or_amount', 12, 2)->nullable();
            $table->string('validity_period')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('utility_bills');
    }
}
