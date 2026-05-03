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
        Schema::create('users_verificationCodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('code', 10);
            $table->string('compare_code', 255)->nullable();
            $table->timestamp('verification_code_expires_at')->nullable();
            $table->boolean('is_reset_password')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // Indexes for better performance
            $table->index('user_id');
            $table->index('code');
            $table->index(['user_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_verificationCodes');
    }
};
