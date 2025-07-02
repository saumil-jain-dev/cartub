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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('cleaner_id')->nullable();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('service_id');

            $table->unsignedBigInteger('wash_type_id');
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('gross_amount', 8, 2)->nullable();
            $table->decimal('discount_amount', 8, 2)->default(0.00);
            $table->decimal('total_amount', 8, 2);
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cleaner_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            // Indexes
            $table->index('customer_id', 'idx_bookings_customer');
            $table->index('cleaner_id', 'idx_bookings_cleaner');
            $table->index('status', 'idx_bookings_status');
            $table->index('scheduled_date', 'idx_bookings_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
