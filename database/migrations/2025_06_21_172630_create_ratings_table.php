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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('cleaner_id');

            $table->integer('rating');
            $table->text('comment')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cleaner_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('booking_id', 'idx_ratings_booking');
            $table->index('customer_id', 'idx_ratings_customer');
            $table->index('cleaner_id', 'idx_ratings_cleaner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
