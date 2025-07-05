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
        Schema::table('main_category_settings', function (Blueprint $table) {

            $table->foreignId('sub_setting_id')->nullable()->constrained('sub_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_category_settings', function (Blueprint $table) {
            $table->dropColumn('sub_settings_id');
        });
    }
};
