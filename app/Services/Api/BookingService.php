<?php

namespace App\Services\Api;

use App\Jobs\Customer\SendMailJob;
use App\Models\Booking;
use App\Models\CleanerEarning;
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

    public function applyCoupon($request)
    {
        try {
            // Assuming you have a Coupon model and logic to validate the coupon
            $couponCode = $request->input('coupon_code');
            $user = Auth::user();
            $userZip = $request->input('zipcode');
            $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', true)
            ->whereDate('valid_from', '<=', Carbon::now())
            ->whereDate('valid_until', '>=', Carbon::now())
            ->first();
            if (!$coupon) {
                return null;
            }
            // if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            //     return null;
            // }
            if (!is_null($coupon->user_ids)) {
                $userIds = json_decode($coupon->user_ids, true) ?? [];
                if (!in_array($user->id, $userIds)) {
                    return null;
                }
            }

            elseif (!is_null($coupon->zipcodes)) {
                $zipcodes = json_decode($coupon->zipcodes, true) ?? [];
                if (!in_array($userZip, $zipcodes)) {
                    return null; // invalid: area not allowed
                }
            }
            return $coupon; // Invalid coupon
        } catch (Exception $e) {
            throw new Exception('Error applying coupon: ' . $e->getMessage());
        }
    }

    public function createBooking($request)
    {
        try {
            DB::beginTransaction();

            $booking = new Booking();
            $booking->booking_number = Booking::generateUniqueOrderNumber();
            $booking->customer_id = $request->input('customer_id');
            $booking->cleaner_id = $request->input('cleaner_id');
            $booking->vehicle_id = $request->input('vehicle_id');
            $booking->add_ons_id = $request->input('add_ons_id',null);
            $booking->service_id = $request->input('service_id');
            $booking->address = $request->input('address');
            $booking->latitude = $request->input('latitude');
            $booking->longitude = $request->input('longitude');
            $booking->notes = $request->input('notes', '');
            $booking->scheduled_date = Carbon::parse($request->input('scheduled_date'));
            $booking->scheduled_time = Carbon::parse($request->input('scheduled_time'));
            $booking->coupon_id = $request->input('coupon_id', null);
            $booking->gross_amount = $request->input('gross_amount');
            $booking->discount_amount = $request->input('discount_amount', 0);
            $booking->total_amount = $request->input('total_amount');
            $booking->payment_status = $request->input('payment_status');

            // Save the booking
            if ($booking->save()) {

                //Save the payment method and transaction ID if provided
                $payment = new Payment();
                $payment->booking_id = $booking->id;
                $payment->amount = $booking->total_amount;
                $payment->payment_method = $request->input('payment_method');
                $payment->transaction_id = $request->input('transaction_id');
                $payment->status = $request->input('payment_status');
                $payment->paid_at = $request->input('payment_status') === 'paid' ? Carbon::now() : null;
                $payment->save();

                // // Increment the used count for the coupon if it exists
                // if (!empty($request->input('coupon_id'))) {
                //     Coupon::where('id', $request->input('coupon_id'))->increment('used_count');
                // }
                DB::commit();
                //send payment mail
                $paymentData = [
                    'customer_name' => Auth::user()->name,
                    'to_email' => Auth::user()->email,
                    'booking_data' => $booking,
                    'payment_data' => $payment,
                    '_blade' => 'payment-confirm',
                    'subject' => '💳 Payment Received'
                ];
                SendMailJob::dispatch($paymentData);
                //send booking mail
                $emailData = [
                    'customer_name' => Auth::user()->name,
                    'to_email' => Auth::user()->email,
                    'booking_data' => $booking,
                    '_blade' => 'booking',
                    'subject' => '✅ Booking Confirmed!'
                ];
                SendMailJob::dispatch($emailData);
                return $booking;
            } else {
                DB::rollBack();
                throw new Exception('Failed to create booking.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error creating booking: ' . $e->getMessage());
        }
    }

    public function listBookings($request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $query = Booking::where('customer_id', Auth::id());

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
                ->where('customer_id', Auth::id())
                ->first();

            if (!$booking) {
                throw new Exception('Booking not found.');
            }

            return $booking;
        } catch (Exception $e) {
            throw new Exception('Error fetching booking details: ' . $e->getMessage());
        }
    }

    public function addRatting($request)
    {
        try {
            DB::beginTransaction();

            $bookingId = $request->input('booking_id');
            $rating = $request->input('rating');
            $review = $request->input('comment', '');

            $booking = Booking::where('id', $bookingId)
                ->where('customer_id', Auth::id())
                ->first();

            if (!$booking) {
                throw new Exception('Booking not found.');
            }

            // Update the booking with rating and review
            $rating = Rating::updateOrCreate(
                ['booking_id' => $booking->id, 'cleaner_id' => $request->input('cleaner_id')],
                [
                    'rating' => $rating,
                    'comment' => $review,
                    'customer_id' => Auth::id(),
                ]
            );
            DB::commit();
            return $booking;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error adding rating: ' . $e->getMessage());
        }
    }

    public function addTip($request)
    {
        try {
            DB::beginTransaction();

            $bookingId = $request->input('booking_id');
            $tipAmount = $request->input('tip_amount');

            $booking = Booking::where('id', $bookingId)
                ->where('customer_id', Auth::id())
                ->first();

            if (!$booking) {
                throw new Exception('Booking not found.');
            }

            if (!empty($tipAmount)) {
                $cleanerEarnings = new CleanerEarning();
                $cleanerEarnings->cleaner_id = $booking->cleaner_id;
                $cleanerEarnings->booking_id = $booking->id;
                $cleanerEarnings->tip = $tipAmount;
                $cleanerEarnings->tip_earned_on = Carbon::now();
                $cleanerEarnings->save();
            }

            DB::commit();
            return $booking;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error adding tip: ' . $e->getMessage());
        }
    }

}
