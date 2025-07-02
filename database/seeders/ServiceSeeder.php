<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $services = [
            [
                'name' => 'Basic Car Wash',
                'description' => 'Exterior hand wash and dry',
                'price' => 199.00,
                'duration_minutes' => 30,
                'type' => 'service',
                'image' => 'services/basic_wash.png',
                'is_active' => true,
            ],
            [
                'name' => 'Interior Detailing',
                'description' => 'Vacuuming, dashboard cleaning, seat wipe',
                'price' => 499.00,
                'duration_minutes' => 60,
                'type' => 'service',
                'image' => 'services/interior_detailing.png',
                'is_active' => true,
            ],
            [
                'name' => 'Premium Package',
                'description' => 'Full exterior + interior service with wax',
                'price' => 899.00,
                'duration_minutes' => 90,
                'type' => 'package',
                'image' => 'services/premium_package.png',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
        
    }
}
