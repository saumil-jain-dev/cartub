<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $customer = User::where('role','customer')->first();

        if (!$customer) {
            $this->command->warn('No customer user found. VehicleSeeder skipped.');
            return;
        }

        $vehicles = [
            [
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2018,
                'color' => 'White',
                'license_plate' => 'GJ01AB1234',
            ],
            [
                'make' => 'Honda',
                'model' => 'Civic',
                'year' => 2020,
                'color' => 'Black',
                'license_plate' => 'GJ02CD5678',
            ],
            [
                'make' => 'Hyundai',
                'model' => 'i20',
                'year' => 2021,
                'color' => 'Red',
                'license_plate' => 'GJ03EF9101',
            ],
        ];

        foreach ($vehicles as $data) {
            Vehicle::create(array_merge($data, [
                'customer_id' => $customer->id, // or ->id based on your PK
            ]));
        }

    }
}
