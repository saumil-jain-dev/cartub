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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('device_id');
            $table->enum('device_type',['ios','android']);
            $table->text('device_token')->nullable();
            $table->string('os_version')->nullable();
            $table->string('app_version')->nullable();
            $table->string('device_name')->nullable();
            $table->string('model_name')->nullable();
            $table->tinyInteger('status')->comment('1-Active, 0-Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
