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
        Schema::table('sub_settings', function (Blueprint $table) {
            $table->foreignId('main_category_setting_id')->nullable()->constrained('main_category_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_settings_settings', function (Blueprint $table) {
            $table->foreignId('main_category_setting_id');
        });
    }
};
