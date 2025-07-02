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
        //
        Schema::table('bookings', function (Blueprint $table) {
            $table->datetime('job_start_time')->nullable()->after('scheduled_time');
            $table->datetime('job_end_time')->nullable()->after('job_start_time');
            $table->integer('job_duration')->nullable()->after('job_end_time'); // duration in minutes
        });
        
        // Modify ENUM column to include new value
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'accepted', 'in_progress', 'completed', 'cancelled', 'mark_as_arrived') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['job_start_time', 'job_end_time', 'job_duration']);
        });
        // Revert ENUM to previous values
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'accepted', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
