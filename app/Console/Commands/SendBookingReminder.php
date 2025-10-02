<?php

namespace App\Console\Commands;

use App\Jobs\SendNotificationJob;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendBookingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-booking-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder to customer and cleaner 30 minutes before booking time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $targetTime = Carbon::now()->addMinutes(30)->format('Y-m-d H:i:00');
        $bookings = Booking::whereRaw("CONCAT(scheduled_date, ' ', scheduled_time) = ?", [$targetTime])
            ->with(['customer', 'cleaner']) // assuming relations are set
            ->get();
        dd($bookings->toArray());

         foreach ($bookings as $booking) {
            $customerMessage = "Reminder: Your CarTub booking {$booking->booking_number} is scheduled at " .
                Carbon::parse($booking->scheduled_date)->format('d F Y') . " " .
                Carbon::parse($booking->scheduled_time)->format('h:i A') . ".";
            $notificationData = [
                'title' => "Upcoming Car Wash",
                "message" =>  $customerMessage,
                'type' => 'booking',
                'payload' => [
                    'booking_id' => $booking->id,
                    'customer_id' => $booking->customer_id,
                    
                ],

            ];
            SendNotificationJob::dispatch($booking->customer_id, $notificationData);
            if($booking->cleaner_id) {
                
                $cleanerMessage = "Reminder: You have a CarTub cleaning job (Booking {$booking->booking_number}) scheduled at " .
                    Carbon::parse($booking->schedule_date)->format('d F Y') . " " .
                    Carbon::parse($booking->schedule_time)->format('h:i A') . ".";
    
                $notificationData = [
                    'title' => "Upcoming Job Reminder",
                    "message" =>  $cleanerMessage,
                    'type' => 'booking',
                    'payload' => [
                        'booking_id' => $booking->id,
                        'customer_id' => $booking->customer_id,
                        'cleaner_id' => $booking->cleaner_id,
                    ],
    
                ];

                SendNotificationJob::dispatch($booking->cleaner_id, $notificationData);
            }
        }
    }
}
