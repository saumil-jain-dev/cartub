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
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->dropColumn('wash_type_id');
            $table->string('add_ons_id')->nullable()->after('cleaner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('wash_type_id')->nullable()->after('cleaner_id');
            $table->dropColumn('add_ons_id');
        });
    }
};
