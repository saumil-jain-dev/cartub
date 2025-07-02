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
        Schema::create('booking_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->enum('photo_type', ['before', 'after'])->default('before');
            $table->string('photo_path')->nullable();
            $table->dateTime('photo_taken_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->index('booking_id', 'idx_booking_photos_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_photos');
    }
};
