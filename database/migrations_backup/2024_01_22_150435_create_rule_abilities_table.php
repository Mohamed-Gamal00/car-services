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
        Schema::create('rule_abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id')->constrained('rules.blade.php')->cascadeOnDelete();
            $table->string('ability');
            $table->enum('type', ['allow', 'deny']);

            $table->unique(['rule_id', 'ability']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rule_abilities');
    }
};
