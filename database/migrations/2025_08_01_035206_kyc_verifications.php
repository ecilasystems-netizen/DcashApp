<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('address');

            // Document Information
            $table->enum('document_type', ['nin', 'passport', 'drivers_license', 'voters_card']);
            $table->string('document_number');
            $table->string('document_front_image')->comment('Path to front of ID card');
            $table->string('document_back_image')->nullable()->comment('Path to back of ID card');
            $table->string('selfie_image')->comment('Path to selfie with ID');

            // Verification Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};
