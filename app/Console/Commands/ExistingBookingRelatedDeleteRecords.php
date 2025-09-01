<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Console\Command;

class ExistingBookingRelatedDeleteRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:existing-booking-related-delete-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Existing bookings and users old data delete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedBookings = Booking::onlyTrashed()->get();
        foreach ($deletedBookings as $booking) {
            $booking->payment()->delete();
            $booking->tip()->delete();
            $booking->rating()->delete();
            $booking->beforePhoto()->delete();
            $booking->afterPhoto()->delete();
        }

        $users = User::onlyTrashed()->get();
        foreach ($users as $user) {
            $user->bookings()->delete();
            $user->bookingCancellations()->delete();
            $user->vehicles()->delete();
            $user->booking()->delete();
            $user->ratings()->delete();
            $user->earnings()->delete();
            $user->devices()->delete();
        }
    }
}
