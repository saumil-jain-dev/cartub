<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'phone' => '7960595810',
                'password' => Hash::make('Password'),
                'role' => 'super_admin',  // optional, if your users table has role column
                'is_active' => true,
            ]
        );

        // Assign super_admin role
        $user->assignRole('super_admin');

        $this->command->info('Admin user with super_admin role seeded successfully!');
    }
}
