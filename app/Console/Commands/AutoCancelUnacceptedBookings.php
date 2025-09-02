<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BookingCancellation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCancelUnacceptedBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bookings-auto-cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto cancel unaccepted bookings after cleaner assignment timeout';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookings = Booking::where('status', 'pending')
            ->whereNotNull('cleaner_id')
            ->whereNotNull('assigned_at')
            ->where('assigned_at', '<=', now()->subMinutes(10)) // 10 min old
            ->get();

        foreach ($bookings as $booking) {

            $booking->update([
                'status' => 'cancelled',
            ]);

            BookingCancellation::create([
                'booking_id' => $booking->id,
                'cleaner_id' => $booking->cleaner_id,
            ]);

            Log::info("Booking #{$booking->id} auto-cancelled due to timeout.");
        }
    }
}
