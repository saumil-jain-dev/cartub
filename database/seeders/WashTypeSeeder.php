<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WashTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('wash_types')->insert([
            ['name' => 'Basic Wash', 'description' => 'Simple exterior wash'],
            ['name' => 'Deluxe Wash', 'description' => 'Exterior + Interior clean'],
            ['name' => 'Premium Wash', 'description' => 'Full detailing package'],
        ]);
    }
}
