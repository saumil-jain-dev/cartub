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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('make', 50);
            $table->string('model', 50);
            $table->integer('year');
            $table->string('color', 30);
            $table->string('license_plate', 20)->nullable();
            $table->boolean('fetched_via_dvla')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('customer_id', 'idx_vehicles_customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
