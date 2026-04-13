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
    Schema::create('shipping_locations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('shipping_company_id')->constrained()->onDelete('cascade');
      $table->foreignId('city_id')->constrained()->onDelete('cascade');
      $table->decimal('shipping_price', 8, 2);
      $table->unique(['shipping_company_id', 'city_id']); // Ensure no duplicates
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('shipping_locations');
  }
};
