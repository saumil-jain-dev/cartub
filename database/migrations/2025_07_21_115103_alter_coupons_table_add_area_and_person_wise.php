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
        //
        Schema::table('coupons', function (Blueprint $table) {
            // Remove old user-wise column if it exists
            if (Schema::hasColumn('coupons', 'user_id')) {
                $table->dropColumn('user_id');
            }

            // Add JSON columns to store multiple IDs/Zipcodes
            $table->json('user_ids')->nullable()->after('deleted_at');   // Person-wise
            $table->json('zipcodes')->nullable()->after('user_ids');     // Area-wise
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('coupons', function (Blueprint $table) {
            // Rollback: remove new columns
            $table->dropColumn('user_ids');
            $table->dropColumn('zipcodes');

            // Optionally add back user_id if needed
            $table->unsignedBigInteger('user_id')->nullable()->after('deleted_at');
        });
    }
};
