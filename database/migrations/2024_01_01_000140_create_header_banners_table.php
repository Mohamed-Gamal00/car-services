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
        Schema::create('header_banners', function (Blueprint $table) {
            $table->id();
            $table->string('header_image')->nullable(); // Arabic banner image
            $table->string('header_image_en')->nullable(); // English banner image
            $table->string('image_link')->nullable(); // Link when banner is clicked
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_banners');
    }
};
