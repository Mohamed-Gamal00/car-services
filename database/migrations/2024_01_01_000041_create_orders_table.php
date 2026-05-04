<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
            $table->foreignId('user_package_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('captain_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('car_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_status_id')->constrained()->onDelete('restrict');
            
            // Car details (can be custom)
            $table->string('car_model')->nullable();
            $table->string('car_number')->nullable();
            
            // Service location
            $table->text('address');
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            
            // Booking details
            $table->date('booking_date');
            $table->time('booking_time');
            $table->text('notes')->nullable();
            
            // Pricing
            $table->decimal('service_price', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            
            // Payment
            $table->enum('payment_method', ['package', 'credit_card', 'cash'])->default('package');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            
            // Status tracking
            $table->boolean('is_arrived')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->boolean('rating_skipped')->default(false);
            
            // Files
            $table->string('invoice_url')->nullable();
            
            $table->timestamps();
            
            $table->index(['booking_date', 'captain_id']);
            $table->index(['user_id', 'booking_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};