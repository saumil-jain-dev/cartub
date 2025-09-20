<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'is_testing'],
            [
                'title' => 'Is testing',
                'value' => 'false',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
