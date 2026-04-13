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
        Schema::create('shipping_types_and_price', function (Blueprint $table) {
            $table->id();
            $table->boolean('add_pickup_from_store')->default(false);
            $table->boolean('add_wight_price')->default(false);
            $table->boolean('add_normal_price')->default(false);
            $table->boolean('add_price_based_on_city')->default(true);

            $table->decimal('weight_price', 8, 2)->default(0);
            $table->decimal('normal_shipping_price', 8, 2)->default(0);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_types_and_price');
    }
};
