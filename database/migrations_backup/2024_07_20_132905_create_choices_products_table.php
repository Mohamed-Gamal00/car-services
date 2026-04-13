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
    Schema::create('choices_products', function (Blueprint $table) {
      $table->id();
      $table->foreignId('choice_id')->constrained('choices')->cascadeOnDelete();
      $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('choices_products');
  }
};
