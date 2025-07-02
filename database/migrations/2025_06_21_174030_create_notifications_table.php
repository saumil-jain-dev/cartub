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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 100);
            $table->text('message');
            $table->enum('type', ['booking', 'payment', 'system']);
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            // Foreign Key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index for fast lookups
            $table->index('user_id');
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
