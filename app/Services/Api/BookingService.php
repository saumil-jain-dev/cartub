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



class BookingService {
    use NotificationTrait;

    // public function applyCoupon($request)
    // {
    //     try {
    //         // Assuming you have a Coupon model and logic to validate the coupon
    //         $couponCode = $request->input('coupon_code');
    //         $user = Auth::user();
    //         $userZip = $request->input('zipcode');
    //         $coupon = Coupon::where('code', $couponCode)
    //                 ->where('is_active', true)
    //                 ->first();
    //         if (!$coupon) {
    //             return null; // coupon not found or inactive
    //         }
    //         if ($coupon->type == 'promo') {
    //             // Check if user is trying to use their own promo code
    //             if ($user->promocode == $couponCode) {
    //                 return null;
    //             }

    //             // Check if user has already used this coupon in bookings
    //             $alreadyUsed = DB::table('bookings')
    //                 ->where('customer_id', $user->id)
    //                 ->where('coupon_id', $coupon->id)
    //                 ->exists();

    //             if ($alreadyUsed) {
    //                 return null; // user already used this coupon once
    //             }
    //         } else {
    //             // Normal coupon logic for all other coupons
    //             $coupon = Coupon::where('code', $couponCode)
    //                 ->where('is_active', true)
    //                 ->whereDate('valid_from', '<=', Carbon::now())
    //                 ->whereDate('valid_until', '>=', Carbon::now())
    //                 ->first();

    //             if (!$coupon) {
    //                 return null;
    //             }
                
    //         $alreadyUsed = DB::table('bookings')
    //                 ->where('customer_id', $user->id)
    //                 ->where('coupon_id', $coupon->id)
    //                 ->exists();
    //         }


    //         // $coupon = Coupon::where('code', $couponCode)
    //         // ->where('is_active', true)
    //         // ->whereDate('valid_from', '<=', Carbon::now())
    //         // ->whereDate('valid_until', '>=', Carbon::now())
    //         // ->first();
    //         // if (!$coupon) {
    //         //     return null;
    //         // }
    //         // if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
    //         //     return null;
    //         // }
    //         if (!is_null($coupon->user_ids)) {
    //             $userIds = json_decode($coupon->user_ids, true) ?? [];
    //             if (!in_array($user->id, $userIds)) {
    //                 return null;
    //             }
                
    //         }

    //         elseif (!is_null($coupon->zipcodes)) {
    //             $zipcodes = json_decode($coupon->zipcodes, true) ?? [];
    //             if (!in_array($userZip, $zipcodes)) {
    //                 return null; // invalid: area not allowed
    //             }
    //         }
    //         //Send notification for coupon applied
    //         $notificationData = [
    //             'title' => "Promo Code Applied!",
    //             "message" =>  "Coupon code ".$coupon->code." applied successfully! Discount added to your booking",
    //             'type' => 'booking',
    //             'payload' => [
    //                 'coupon_id' => $coupon->id,
    //                 'customer_id' => $user->id,
    //             ],

    //         ];
    //         // $this->save_notification($user->id,$notificationData);
    //         return $coupon;
    //     } catch (Exception $e) {
    //         throw new Exception('Error applying coupon: ' . $e->getMessage());
    //     }
    // }
    public function applyCoupon($request)
    {
        try {
            $couponCode = $request->input('coupon_code');
            $user = Auth::user();
            $userZip = $request->input('zipcode');
            
            $coupon = Coupon::where('code', $couponCode)
                    ->where('is_active', true)
                    ->first();
            
            if (!$coupon) {
                return null; // coupon not found or inactive
            }
            
            // Check if user is trying to use their own promo code
            if ($coupon->type == 'promo' && $user->promocode == $couponCode) {
                return null;
            }
            
            // Check if user has already used this coupon (one-time use for all coupon types)
            $alreadyUsed = DB::table('bookings')
                ->where('customer_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->exists();
            
            if ($alreadyUsed) {
                return null; // user already used this coupon
            }
            
            // Date validation for regular coupons only
            if ($coupon->type != 'promo') {
                $isValidDate = Carbon::now()->between(
                    Carbon::parse($coupon->valid_from),
                    Carbon::parse($coupon->valid_until)
                );
                
                if (!$isValidDate) {
                    return null; // coupon expired or not yet valid
                }
            }
            
            // Usage limit check
            if (!is_null($coupon->usage_limit) && $coupon->used_count >= $coupon->usage_limit) {
                return null; // usage limit reached
            }
            
            // User restrictions check
            if (!is_null($coupon->user_ids)) {
                $userIds = json_decode($coupon->user_ids, true) ?? [];
                if (!in_array($user->id, $userIds)) {
                    return null; // user not allowed to use this coupon
                }
            }
            // Zipcode restrictions check
            elseif (!is_null($coupon->zipcodes)) {
                $zipcodes = json_decode($coupon->zipcodes, true) ?? [];
                if (!in_array($userZip, $zipcodes)) {
                    return null; // invalid: area not allowed
                }
            }
            
            // Send notification for coupon applied
            $notificationData = [
                'title' => "Promo Code Applied!",
                'message' => "Coupon code " . $coupon->code . " applied successfully! Discount added to your booking",
                'type' => 'booking',
                'payload' => [
                    'coupon_id' => $coupon->id,
                    'customer_id' => $user->id,
                ],
            ];
            // $this->save_notification($user->id, $notificationData);
            
            return $coupon;
            
        } catch (Exception $e) {
            throw new Exception('Error applying coupon: ' . $e->getMessage());
        }
    }

    public function createBooking($request,$firebaseService)
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

                // Update user promo bonus amount if promo code used
                
                if (!empty($request->input('coupon_id'))) {
                    $Coupon = Coupon::where('id', $request->input('coupon_id'))->first();

                    if ($Coupon && $Coupon->type == 'promo') {
                        // Find the user who has this promo code
                        $userWithPromo = User::where('promocode', $Coupon->code)->first();

                        if ($userWithPromo) {
                            $userWithPromo->promo_bonus_amount += $Coupon->discount_value;
                            $userWithPromo->save();
                        }
                    }
                }
                if(!empty($request->promo_used_amount)){
                    $user->promo_bonus_amount -= $request->promo_used_amount;
                    if($user->promo_bonus_amount < 0){
                        $user->promo_bonus_amount = 0;
                    }
                    $user->save();

                }
                DB::commit();

                //Send Booking SMS
                $phone = $user->country_code.$user->phone;
                $message = "Confirmed! your CarTub mobile wash is scheduled for " .
                    Carbon::parse($booking->scheduled_date)->format('M j, Y') . ' at ' .
                    Carbon::parse($booking->scheduled_time)->format('h:i A') .
                    ".Booking #{$booking->booking_number}. See you then.";
                SendSMSJob::dispatch($phone,$message);

                //Send Booking SMS to SuperAdmin
                $adminMessage = "New Job " .
                "#{$booking->booking_number}: " .
                "{$booking->customer->name} ({$booking->customer->phone})- " .
                "{$booking->address}. " .

                Carbon::parse($booking->scheduled_date)->format('d F Y') . " at " .
                Carbon::parse($booking->scheduled_time)->format('h:i A') .
                ". {$booking->vehicle->license_plate}";
                $adminUser = User::where('role','super_admin')->first();
                $admin_phone = $adminUser->country_code.$adminUser->phone;
                SendSMSJob::dispatch($admin_phone,$adminMessage);

                //Send booking notification
                $notificationData = [
                    'title' => "Booking Confirmed!",
                    "message" =>  "Your car wash has been successfully booked for ".Carbon::parse($booking->scheduled_date)->format('d F Y')." at ".Carbon::parse($booking->scheduled_time)->format('h:i A').". Cleaner details will be shared shortly.",
                    'type' => 'booking',
                    'payload' => [
                        'booking_id' => (string)$booking->id,
                        'booking_number' => (string)$booking->booking_number,
                        'customer_id' => (string)$booking->customer_id,
                    ],

                ];
                $this->save_notification($booking->customer_id,$notificationData);

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
                // SendMailJob::dispatch($paymentData);
                //send booking mail
                $emailData = [
                    'customer_name' => Auth::user()->name,
                    'to_email' => Auth::user()->email,
                    'booking_data' => $booking,
                    '_blade' => 'booking',
                    'subject' => '🚘 Your Car Wash is Booked - See You Soon!'
                ];
                SendMailJob::dispatch($emailData);

                //Store to database
                $firebaseService->storeBooking([
                    'id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'customer_name' => $booking->customer->name,
                    'status' => $booking->status,
                    'scheduled_date' => $booking->scheduled_date,
                    'scheduled_time' => $booking->scheduled_time,
                    'vehicle_number' => $booking->vehicle->license_plate,
                    'vehicle_model' => $booking->vehicle->model,
                    'total_amount' => $booking->total_amount,
                    'payment_status' => $booking->payment_status,
                    'created_at' => $booking->created_at->toIso8601String(),
                    'payment_method' => $booking->payment->payment_method,
                ]);
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

            //Send notification to cleaner
            $notificationData = [
                'title' => "Customer Feedback Received",
                "message" =>  "You received a rating of ".$request->input('rating')."⭐ from your last customer. View details in your profile.",
                'type' => 'booking',
                'payload' => [
                    'booking_id' => (string)$booking->id,
                    'cleaner_id' => (string)$request->input('cleaner_id'),
                ],

            ];
            $this->save_notification($request->input('cleaner_id'),$notificationData);
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

                $cleanerEarnings = CleanerEarning::updateOrCreate([
                    'cleaner_id' => $booking->cleaner_id,
                    'booking_id' => $booking->id,
                ],[
                    'tip' => $tipAmount ?? 0,
                    'tip_earned_on' => Carbon::now()
                ]);
                // $cleanerEarnings = new CleanerEarning();
                // $cleanerEarnings->cleaner_id = $booking->cleaner_id;
                // $cleanerEarnings->booking_id = $booking->id;
                // $cleanerEarnings->tip = $tipAmount;
                // $cleanerEarnings->tip_earned_on = Carbon::now();
                // $cleanerEarnings->save();
            }

            DB::commit();

            //Send notification to cleaner
            $notificationData = [
                'title' => "💰 New Tip Received!",
                "message" =>  "You've received a tip from your customer for your great service. Keep up the good work! 👏",
                'type' => 'booking',
                'payload' => [
                    'booking_id' => (string)$booking->id,
                    'cleaner_id' => (string)$request->input('cleaner_id'),
                ],

            ];
            $this->save_notification($booking->cleaner_id,$notificationData);
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
