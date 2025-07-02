<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $coupons = [
            [
                'code' => 'WELCOME10',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'min_amount' => 100.00,
                'usage_limit' => 100,
                'used_count' => 0,
                'valid_from' => Carbon::now()->subDays(1)->toDateString(),
                'valid_until' => Carbon::now()->addDays(30)->toDateString(),
                'is_active' => true,
            ],
            [
                'code' => 'FLAT50',
                'discount_type' => 'fixed',
                'discount_value' => 50.00,
                'min_amount' => 500.00,
                'usage_limit' => 50,
                'used_count' => 0,
                'valid_from' => Carbon::now()->toDateString(),
                'valid_until' => Carbon::now()->addDays(15)->toDateString(),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER20',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'min_amount' => 200.00,
                'usage_limit' => 20,
                'used_count' => 5,
                'valid_from' => Carbon::now()->subDays(5)->toDateString(),
                'valid_until' => Carbon::now()->addDays(10)->toDateString(),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::create($data);
        }
    }
}
