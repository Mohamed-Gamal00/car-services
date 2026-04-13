<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique(); // Payment reference
            $table->integer('remaining_washes');
            $table->date('start_date');
            $table->date('expiry_date');
            $table->enum('status', ['inactive', 'active', 'expired', 'used_up'])->default('inactive');
            $table->boolean('notified_expired')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_packages');
    }
};