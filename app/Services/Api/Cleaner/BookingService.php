<?php

namespace App\Services\Api\Cleaner;

use App\Jobs\Customer\SendMailJob;
use App\Jobs\SendSMSJob;
use App\Models\Booking;
use App\Models\BookingCancellation;
use App\Models\BookingPhoto;
use App\Models\CleanerEarning;
use App\Models\CleanerLocation;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\Rating;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
use App\Traits\NotificationTrait;

class BookingService {

    use NotificationTrait;
    public function assignBookingList($request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $query = Booking::with(['vehicle','service','washType'])->where('cleaner_id', Auth::id())->where('status', 'pending');

            if ($request->has('booking_date')) {
                $bookingDate = Carbon::parse($request->input('booking_date'));
                $query->whereDate('created_at', $bookingDate);
            }

            return $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        } catch (Exception $e) {
            throw new Exception('Error listing bookings: ' . $e->getMessage());
        }
    }

    public function bookingDetails($request)
    {
        try {
            $bookingId = $request->input('booking_id');
            $booking = Booking::with(['customer', 'cleaner', 'vehicle', 'washType', 'service','beforePhoto', 'afterPhoto','rating', 'tip','cleaner_location'])
                ->where('id', $bookingId)
                ->where('cleaner_id', Auth::id())
                ->first();

            if (!$booking) {
                throw new Exception('Booking not found.');
            }

            return $booking;
        } catch (Exception $e) {
            throw new Exception('Error fetching booking details: ' . $e->getMessage());
        }
    }

    public function updateBookingStatus($request)
    {
        try {
            DB::beginTransaction();
            $bookingId = $request->input('booking_id');
            $action = $request->input('action');

            $booking = Booking::where('id', $bookingId)->where('cleaner_id', Auth::id())->first();

            if (!$booking) {
                throw new Exception('Booking not found.');
            }

            switch ($action) {
                case 'accept':
                    $booking->status = 'accepted';
                    break;
                case 'in_route':
                    $booking->status = 'in_route';
                    //Send notification to customer cleaner start ride
                    $notificationData = [
                        'title' => "Cleaner On the Way",
                        "message" =>  "Your car cleaner ".$booking->cleaner->name." is on the way and will arrive shortly. Track live status in the app.",
                        'type' => 'booking',
                        'payload' => [
                            'booking_id' => (string)$booking->id,
                            'booking_number' => (string)$booking->booking_number,
                            'cleaner_id' => (string)$booking->cleaner_id,
                            'customer_id' => (string)$booking->customer_id,
                        ],

                    ];
                    $this->save_notification($booking->customer_id,$notificationData);
                    break;
                case 'mark_as_arrived':
                    $booking->status = 'mark_as_arrived';
                    $message = "Your CarTub specialist has arrived at your location and is ready to begin. We are at your vehicle now. Thank you.";
                    $phone = $booking->customer->country_code.$booking->customer->phone;
                    SendSMSJob::dispatch($phone, $message);
                    break;
                case 'start_job':
                    $booking->status = 'in_progress';
                    $booking->job_start_time = Carbon::now();

                    if($request->hasFile('before_image')){
                        $before_image = uploadMultipleImages($request->file('before_image'), 'job_image/'.$booking->id.'/before') ?? [];

                    }
                    if(isset($before_image) && $before_image) {
                        foreach ($before_image as $image) {
                            BookingPhoto::create([
                                'booking_id' => $booking->id,
                                'photo_path' => $image,
                                'photo_type' => 'before',
                                'photo_taken_at' => Carbon::now(),
                            ]);
                        }
                    }

                    //Send notification to customer cleaner start job
                    $notificationData = [
                        'title' => "Car Wash in Progress",
                        "message" =>  "Your car wash has started. Sit back and relax while we shine your ride.",
                        'type' => 'booking',
                        'payload' => [
                            'booking_id' => (string)$booking->id,
                            'booking_number' => (string)$booking->booking_number,
                            'cleaner_id' => (string)$booking->cleaner_id,
                            'customer_id' => (string)$booking->customer_id,
                        ],

                    ];
                    $this->save_notification($booking->customer_id,$notificationData);
                    break;
                case 'finish_job':

                    $booking->job_end_time = Carbon::now();
                    $booking->job_duration = Carbon::parse($booking->job_start_time)->diffInMinutes(Carbon::now());
                    break;
                case 'complete':
                    $booking->status = 'completed';
                    $booking->cleaner_note = $request->input('cleaner_note', null);

                    if($request->hasFile('after_image')){
                        $after_image = uploadMultipleImages($request->file('after_image'), 'job_image/'.$booking->id.'/after');

                    }
                    if(isset($after_image) && $after_image) {
                        foreach ($after_image as $image) {
                            BookingPhoto::create([
                                'booking_id' => $booking->id,
                                'photo_path' => $image,
                                'photo_type' => 'after',
                                'photo_taken_at' => Carbon::now(),
                            ]);
                        }
                    }
                    $cleaner_commission = getSettingsData('cleaner_commission');
                    $cleanerEarnings = new CleanerEarning();
                    $cleanerEarnings->cleaner_id = $booking->cleaner_id;
                    $cleanerEarnings->booking_id = $booking->id;
                    $cleanerEarnings->amount = $cleaner_commission ?? 0;
                    $cleanerEarnings->earned_on = Carbon::now();
                    $cleanerEarnings->save();

                    $bookingData = Booking::with(['cleaner','customer','afterPhoto'])->where('id', $bookingId)->first();

                    //Send notification to customer cleaner complete job
                    $notificationData = [
                        'title' => "Car Wash Complete",
                        "message" =>  "Your car wash is complete. Hope you loved it! Check your dashboard for details.",
                        'type' => 'booking',
                        'payload' => [
                            'booking_id' => (string)$booking->id,
                            'booking_number' => (string)$booking->booking_number,
                            'cleaner_id' => (string)$booking->cleaner_id,
                            'customer_id' => (string)$booking->customer_id,
                        ],

                    ];
                    $this->save_notification($booking->customer_id,$notificationData);

                    //Send notification to customer Feedback
                    $notificationData = [
                        'title' => "How Did We Do?",
                        "message" =>  "We'd love to hear from you! Please rate your recent car wash experience.",
                        'type' => 'booking',
                        'payload' => [
                            'booking_id' => (string)$booking->id,
                            'booking_number' => (string)$booking->booking_number,
                            'cleaner_id' => (string)$booking->cleaner_id,
                            'customer_id' => (string)$booking->customer_id,
                        ],

                    ];
                    $this->save_notification($booking->customer_id,$notificationData);

                    //send booking complete mail
                    $paymentData = [
                        'customer_name' => $bookingData->customer->name ?? "",
                        'to_email' => $bookingData->customer->email ?? "",
                        'booking_data' => $bookingData,
                        '_blade' => 'booking-complete',
                        'subject' => 'Your CarTub Service is Complete ✅'
                    ];
                    SendMailJob::dispatch($paymentData);

                    break;
                case 'cancel':
                    $booking->status = 'pending';
                    // Optionally, you can handle any additional logic for cancellation here
                    $bookingCancel = BookingCancellation::create([
                        'booking_id' => $booking->id,
                        'cleaner_id' => Auth::id(),
                    ]);

                    // $notificationData = [
                    //     'title' => "Booking Cancelled",
                    //     "message" =>  "Your booking on ".$booking->scheduled_date." has been cancelled. You can rebook anytime from the app.",
                    //     'type' => 'booking',
                    //     'payload' => [
                    //         'booking_id' => (string)$booking->id,
                    //         'cleaner_id' => $booking->cleaner_id !== null ? (string)$booking->cleaner_id : '', // convert null to empty string
                    //         'customer_id' => (string)$booking->customer_id,
                    //     ],

                    // ];
                    // $this->save_notification($booking->customer_id,$notificationData);

                    break;
                default:
                    throw new Exception('Invalid action.');
            }

            $booking->save();
            DB::commit();
            return $booking;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error updating booking status: ' . $e->getMessage());
        }
    }

    public function getBookingsList($request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $query = Booking::with(['vehicle','service','washType'])->where('cleaner_id', Auth::id())->where('status', "!=",'pending');

            if ($request->has('booking_date')) {
                $bookingDate = Carbon::parse($request->input('booking_date'));
                $query->whereDate('created_at', $bookingDate);
            }

            return $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        } catch (Exception $e) {
            throw new Exception('Error listing bookings: ' . $e->getMessage());
        }
    }

    public function addCleanerWashTime($request)
    {
        $booking = Booking::findOrFail($request->booking_id);

        if($booking){
            $booking->wash_time = $request->time;
            $booking->save();
        }
        return $booking;
    }

}
