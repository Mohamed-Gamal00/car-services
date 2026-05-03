<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Rename existing columns to Arabic versions
            $table->renameColumn('brand', 'brand_ar');
            $table->renameColumn('model', 'model_ar');
        });
        
        Schema::table('cars', function (Blueprint $table) {
            // Add English columns
            $table->string('brand_en')->nullable()->after('brand_ar');
            $table->string('model_en')->nullable()->after('model_ar');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['brand_en', 'model_en']);
        });
        
        Schema::table('cars', function (Blueprint $table) {
            $table->renameColumn('brand_ar', 'brand');
            $table->renameColumn('model_ar', 'model');
        });
    }
};
