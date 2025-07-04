<?php

namespace App\Services\Api\Cleaner;

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

class BookingService {
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
                case 'mark_as_arrived':
                    $booking->status = 'mark_as_arrived';
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

                    $cleanerEarnings = new CleanerEarning();
                    $cleanerEarnings->cleaner_id = $booking->cleaner_id;
                    $cleanerEarnings->booking_id = $booking->id;
                    $cleanerEarnings->amount = $booking->total_amount;
                    $cleanerEarnings->earned_on = Carbon::now();
                    $cleanerEarnings->save();

                    break;
                case 'cancel':
                    $booking->status = 'pending';
                    // Optionally, you can handle any additional logic for cancellation here
                    $bookingCancel = BookingCancellation::create([
                        'booking_id' => $booking->id,
                        'cleaner_id' => Auth::id(),
                    ]);
                    
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

}
