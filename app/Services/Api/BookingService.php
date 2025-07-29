<?php

namespace App\Services\Api;

use App\Jobs\Customer\SendMailJob;
use App\Jobs\SendSMSJob;
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
use App\Traits\NotificationTrait;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Kreait\Firebase\Factory;




class BookingService {
    use NotificationTrait;

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
            //Send notification for coupon applied
            $notificationData = [
                'title' => "Promo Code Applied!",
                "message" =>  "Coupon code ".$coupon->code." applied successfully! Discount added to your booking",
                'type' => 'booking',
                'payload' => [
                    'coupon_id' => $coupon->id,
                    'customer_id' => $user->id,
                ],

            ];
            // $this->save_notification($user->id,$notificationData);
            return $coupon;
        } catch (Exception $e) {
            throw new Exception('Error applying coupon: ' . $e->getMessage());
        }
    }

    public function createBooking($request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $booking = new Booking();
            $booking->booking_number = Booking::generateUniqueOrderNumber();
            $booking->customer_id = $user->id;
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
            $booking->device_id  = $request->input('device_id', null);

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

                //Send Booking SMS
                $phone = $user->country_code.$user->phone;
                $message = "Your CarTub booking #{$booking->booking_number} is confirmed for " .
                    Carbon::parse($booking->scheduled_date)->format('d M Y') . ', ' .
                    Carbon::parse($booking->scheduled_time)->format('h:i A') .
                    ". Thank you for choosing CarTub.";
                SendSMSJob::dispatch($phone,$message);

                //Send Booking SMS to SuperAdmin
                $adminMessage = "New booking alert! " .
                "Booking #: #{$booking->booking_number}. " .
                "Customer: {$booking->customer->name} ({$booking->customer->phone}). " .
                "Address: {$booking->address}. " .
                "Scheduled for: " .
                Carbon::parse($booking->scheduled_date)->format('d M Y') . " at " .
                Carbon::parse($booking->scheduled_time)->format('h:i A') . 
                ". Please check the admin panel for details.";
                $adminUser = User::where('role','super_admin')->first();
                $admin_phone = $adminUser->country_code.$adminUser->phone;
                SendSMSJob::dispatch($admin_phone,$adminMessage);

                //Send booking notification
                $notificationData = [
                    'title' => "Booking Confirmed!",
                    "message" =>  "Your car wash has been successfully booked for ".Carbon::parse($booking->scheduled_date)->format('d M Y')." at ".Carbon::parse($booking->scheduled_time)->format('d M Y').". Cleaner details will be shared shortly.",
                    'type' => 'booking',
                    'payload' => [
                        'booking_id' => $booking->id,
                        'booking_number' => $booking->booking_number,
                        'customer_id' => $booking->customer_id,
                    ],

                ];
                // $this->save_notification($booking->customer_id,$notificationData);

                $paymentNotification = [
                    'title' => "Payment Received!",
                    "message" =>  "We've received your payment of $".$booking->total_amount." for your recent car wash. Thank you!",
                    'type' => 'payment',
                    'payload' => [
                        'booking_id' => $booking->id,
                        'booking_number' => $booking->booking_number,
                        'customer_id' => $booking->customer_id,
                    ],
                ];
                // $this->save_notification($booking->customer_id,$paymentNotification);
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
           
            // At the end of createBooking(), just before "return $booking"
            $firebase = (new Factory)
                ->withServiceAccount(storage_path('app/firebase/firebase.json'))
                ->createDatabase();
            
            $firebase->getReference('bookings/'.$booking->id)->set([
                'id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'customer_id' => $booking->customer_id,
                'customer_name' => $booking->customer->name,
                'address' => $booking->address,
                'scheduled_date' => $booking->scheduled_date->format('Y-m-d'),
                'scheduled_time' => $booking->scheduled_time->format('H:i:s'),
                'status' => $booking->status,
                'total_amount' => $booking->total_amount,
                'created_at' => $booking->created_at->toDateTimeString()
            ]);
    
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
            
            //Send notification to cleaner
            $notificationData = [
                'title' => "Customer Feedback Received",
                "message" =>  "You received a rating of ".$rating."⭐ from your last customer. View details in your profile.",
                'type' => 'booking',
                'payload' => [
                    'booking_id' => $booking->id,
                    'cleaner_id' => $request->input('cleaner_id'),
                ],

            ];
            // $this->save_notification($request->input('cleaner_id'),$notificationData);
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

    public function createIntent($request)
    {
        try {
            $amount = $request->input('amount');
            if ($amount <= 0) {
                throw new Exception('Invalid amount for payment intent.');
            }

            Stripe::setApiKey(config('constants.STRIPE_SECRET'));
            $currency = 'gbp';
            

            $customer_id = Auth::user()->customer_stripe_id;
            if(!$customer_id) {
                $customer = Customer::create([
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]);

                $customer_id = $customer->id;
                Auth::user()->update(['customer_stripe_id' => $customer_id]);
            }

            $ephemeralKey = EphemeralKey::create(
                ['customer' => $customer_id],
                ['stripe_version' => '2024-04-10'] // must set Stripe version here
            );

            $intent = PaymentIntent::create([
                'amount' => intval($amount * 100),
                'currency' => $currency,
                'customer' => $customer_id,
                'payment_method_types' => ['card'],
            ]);


            return [
                'client_secret' => $intent->client_secret, // Replace with actual client secret from payment gateway
                'customer_id' => $customer_id,
                'ephemeral_key' => $ephemeralKey->secret, // Return the ephemeral key ID
                'amount' => $amount,
                'intent_id' => $intent->id,
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Stripe-specific error
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            // General error
            throw new Exception($e->getMessage());
            
        }
    }

    public function checkPaymentStatus($request){
        try {
            $paymentIntentId = $request->payment_intent_id;
            Stripe::setApiKey(config('constants.STRIPE_SECRET'));
            $intent = PaymentIntent::retrieve($paymentIntentId);
            return $intent;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Stripe-specific error
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            // General error
            throw new Exception($e->getMessage());
            
        }
    }
}
