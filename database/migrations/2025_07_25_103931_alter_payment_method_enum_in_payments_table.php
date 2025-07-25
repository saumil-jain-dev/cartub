<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('card', 'google_pay', 'apple_pay', 'cod') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('card', 'google_pay', 'apple_pay') NOT NULL");
        });
    }
};
