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
        Schema::create('cleaner_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cleaner_id');
            $table->unsignedBigInteger('booking_id');

            $table->decimal('amount', 8, 2)->nullable();
            $table->decimal('tip', 8, 2)->nullable();
            $table->decimal('bonus', 8, 2)->nullable();

        
            $table->dateTime('earned_on')->nullable();
            $table->dateTime('tip_earned_on')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('cleaner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');

            // Indexes (optional)
            $table->index('cleaner_id');
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cleaner_earnings');
    }
};
