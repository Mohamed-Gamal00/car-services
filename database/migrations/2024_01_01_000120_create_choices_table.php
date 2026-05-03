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
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Arabic name
            $table->string('name_en')->nullable(); // English name
            $table->string('image')->nullable();
            $table->decimal('service_price', 10, 2)->default(0); // Additional service price
            $table->timestamps();
        });

        // Pivot table for choices and orders
        Schema::create('order_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('choice_id')->constrained('choices')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_choices');
        Schema::dropIfExists('choices');
    }
};
