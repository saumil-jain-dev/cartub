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
        Schema::create('cleaner_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cleaner_id');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('is_available')->default(true);

            $table->timestamp('location_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();


            // Foreign key
            $table->foreign('cleaner_id')->references('id')->on('users')->onDelete('cascade');

            // Optional index for quick lookup
            $table->index('cleaner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cleaner_locations');
    }
};
