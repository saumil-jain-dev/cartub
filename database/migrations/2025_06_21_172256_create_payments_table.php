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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');

            $table->decimal('amount', 8, 2);
            $table->enum('payment_method', ['card', 'google_pay', 'apple_pay']);
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
