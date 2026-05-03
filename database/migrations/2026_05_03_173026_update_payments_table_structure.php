<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('payments', 'user_name')) {
                $table->string('user_name')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('payments', 'order_number')) {
                $table->string('order_number')->nullable()->after('user_name');
            }
            
            if (!Schema::hasColumn('payments', 'package_reference')) {
                $table->string('package_reference')->nullable()->after('order_number');
            }
            
            if (!Schema::hasColumn('payments', 'source')) {
                $table->string('source')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('payments', 'cur')) {
                $table->string('cur', 3)->default('SAR')->after('amount');
            }
            
            if (!Schema::hasColumn('payments', 'description')) {
                $table->text('description')->nullable()->after('gateway_response');
            }
            
            // Make reference nullable to handle cases where it might be empty
            // Drop unique constraint if exists and recreate with nullable
            if (Schema::hasColumn('payments', 'reference')) {
                $table->string('reference')->nullable()->change();
            }
        });
        
        // Update any empty string references to null
        DB::table('payments')->where('reference', '')->update(['reference' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'user_name')) {
                $table->dropColumn('user_name');
            }
            
            if (Schema::hasColumn('payments', 'order_number')) {
                $table->dropColumn('order_number');
            }
            
            if (Schema::hasColumn('payments', 'package_reference')) {
                $table->dropColumn('package_reference');
            }
            
            if (Schema::hasColumn('payments', 'source')) {
                $table->dropColumn('source');
            }
            
            if (Schema::hasColumn('payments', 'cur')) {
                $table->dropColumn('cur');
            }
            
            if (Schema::hasColumn('payments', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
