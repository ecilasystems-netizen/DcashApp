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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('content_type', ['image', 'video', 'slider']);

            // Use a JSON column to flexibly store paths for single images, sliders, or video URLs.
            $table->json('content');

            $table->string('cta_text')->nullable(); // Call-to-action button text (e.g., "Learn More")
            $table->string('cta_link')->nullable(); // URL for the button

            // Performance tracking columns
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable(); // Optional: schedule start time
            $table->timestamp('ends_at')->nullable();   // Optional: schedule end time
            //status
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
