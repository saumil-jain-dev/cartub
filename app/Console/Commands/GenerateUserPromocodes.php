<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class GenerateUserPromocodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-promocodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate unique 10-character promo codes for all existing customer users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Generating promo codes for customer users...');
        $users = User::where('role', 'customer')
            ->whereNull('promocode')
            ->get();
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        foreach ($users as $user) {
            $namePart = strtoupper(Str::substr(preg_replace('/\s+/', '', $user->name), 0, 5));
            $remainingLength = 10 - strlen($namePart);
            $randomPart = strtoupper(Str::random($remainingLength));
            $promoCode = $namePart . $randomPart;

            // Ensure promo code uniqueness
            while (User::where('promocode', $promoCode)->exists()) {
                $promoCode = $namePart . strtoupper(Str::random($remainingLength));
            }

            $user->promocode = $promoCode;
            $user->save();


            $coupon = new Coupon();
            $coupon->code = $promoCode;
            $coupon->type = 'promo';
            $coupon->discount_type = 'fixed';
            $coupon->discount_value = 5;
            $coupon->valid_from = now();
            $coupon->valid_until = now();
            $coupon->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Promo code generation completed successfully!');
    }
}
