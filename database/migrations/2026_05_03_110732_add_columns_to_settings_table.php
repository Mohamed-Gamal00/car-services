<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Drop key-value columns if they exist
            if (Schema::hasColumn('settings', 'key')) {
                $table->dropUnique(['key']);
                $table->dropColumn(['key', 'value', 'type', 'group', 'description']);
            }
            
            // Add new columns for single-row settings
            $table->string('website_name')->nullable();
            $table->string('website_name_en')->nullable();
            $table->text('address')->nullable();
            $table->text('address_en')->nullable();
            $table->text('subscription_title')->nullable();
            $table->text('subscription_title_en')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('whatsaap')->nullable();
            $table->string('publishable_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('sms_api_key')->nullable();
            $table->string('sms_user_name')->nullable();
            $table->string('sernder')->nullable();
            $table->string('working_strat_time')->nullable();
            $table->string('working_end_time')->nullable();
            $table->string('start_rest_time')->nullable();
            $table->string('end_rest_time')->nullable();
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'website_name', 'website_name_en', 'address', 'address_en',
                'subscription_title', 'subscription_title_en', 'email', 'phone_number',
                'whatsaap', 'publishable_key', 'secret_key', 'sms_api_key',
                'sms_user_name', 'sernder', 'working_strat_time', 'working_end_time',
                'start_rest_time', 'end_rest_time', 'logo', 'image'
            ]);
            
            // Restore key-value columns
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general');
            $table->text('description')->nullable();
        });
    }
};
