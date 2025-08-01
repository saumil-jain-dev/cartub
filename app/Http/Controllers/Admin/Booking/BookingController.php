<?php

namespace App\Http\Controllers\Admin\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Service;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use App\Traits\NotificationTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    //
    use NotificationTrait;
    protected $data;

    public function index(Request $request){

        if(! hasPermission('bookings.index')){
            abort(403);
        }
        
        $this->data['pageTitle'] = 'All Bookings';
        
        $bookings = Booking::with(['customer', 'payment']);

        // Filters
        if ($request->filled('from_date')) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $bookings->whereDate('created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $bookings->whereDate('created_at', '<=', $toDate);
        }

        if ($request->filled('payment_status')) {
            $bookings->where('payment_status',$request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $bookings->whereHas('payment', function ($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        $this->data['bookingData'] = $bookings->orderBy('bookings.id','desc')->get();
        
        return view('admin.bookings.index',$this->data);
    }

    public function create(){
        $this->data['pageTitle'] = 'Manual Booking';
        $this->data['users'] = User::where('role','customer')->where('is_active',1)->get();
        $this->data['wash_types'] = Service::where('is_active',true)->where('type','service')->orderBy('id', 'desc')->get();
        $this->data['services'] = Service::where('is_active',true)->where('type','package')->orderBy('id', 'desc')->get();
        $this->data['coupons'] = Coupon::where('is_active',true)->where('valid_until','>',now())->orderBy('id','desc')->get();
        return view('admin.bookings.create',$this->data);
    }
    
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'customer_id' => 'required|exists:users,id',
                'vehicle_id' => 'required|exists:vehicles,id',
                'contact' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string|max:500',
                'country' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'postal_code' => 'required|string|max:20',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'service_id' => 'required|exists:services,id',
                'add_ons_id' => 'nullable|array',
                'add_ons_id.*' => 'exists:services,id',
                'payment_method' => 'required|in:cod,card,online',
                'coupon_id' => 'nullable|exists:coupons,id',
                'coupon_code' => 'nullable|string|max:50',
                'subtotal' => 'required|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
            ]);

            $scheduleDatetime = $request->input('scheduleDatetime');
            $datetime = \Carbon\Carbon::parse($scheduleDatetime);
            $scheduleDate = $datetime->toDateString();
            $scheduleTime = $datetime->toTimeString();

            DB::beginTransaction();

            // Create the booking
            $booking = new Booking();
            $booking->booking_number = $this->generateUniqueOrderNumber();
            $booking->customer_id = $validated['customer_id'];
            $booking->cleaner_id = null; // Will be assigned later
            $booking->vehicle_id = $validated['vehicle_id'];
            $booking->add_ons_id = !empty($validated['add_ons_id']) ? json_encode($validated['add_ons_id']) : null;
            $booking->service_id = $validated['service_id'];
            $booking->address = $validated['address'];
            $booking->latitude = $validated['latitude'] ?? null;
            $booking->longitude = $validated['longitude'] ?? null;
            $booking->notes = '';
            $booking->scheduled_date = $scheduleDate;
            $booking->scheduled_time = $scheduleTime;
            $booking->coupon_id = $validated['coupon_id'] ?? null;
            $booking->gross_amount = $validated['subtotal'];
            $booking->discount_amount = $validated['discount_amount'] ?? 0;
            $booking->total_amount = $validated['total_amount'];
            $booking->payment_status = 'pending';

            // Save the booking
            if ($booking->save()) {

                // Save the payment method and transaction ID
                $payment = new Payment();
                $payment->booking_id = $booking->id;
                $payment->amount = $booking->total_amount;
                $payment->payment_method = $validated['payment_method'];
                $payment->transaction_id = null; // No transaction ID for manual bookings
                $payment->status = 'pending';
                $payment->paid_at = null;
                $payment->save();

                // Handle coupon usage if applied
                if (!empty($validated['coupon_id'])) {
                    $coupon = Coupon::where('id', $validated['coupon_id'])
                                   ->where('is_active', true)
                                   ->first();
                    
                    // if ($coupon) {
                    //     CouponUsage::create([
                    //         'coupon_id' => $validated['coupon_id'],
                    //         'user_id' => $validated['customer_id'],
                    //         'booking_id' => $booking->id,
                    //         'used_at' => now(),
                    //     ]);
                    // }
                }

                DB::commit();
                $customer = User::find($booking->customer_id);
                // Send Booking SMS
                $phone = $customer->country_code . $customer->phone;
                $message = "Your CarTub booking #{$booking->booking_number} is confirmed for " .
                    Carbon::parse($booking->scheduled_date)->format('d M Y') . ', ' .
                    Carbon::parse($booking->scheduled_time)->format('h:i A') .
                    ". Thank you for choosing CarTub.";
                // \App\Jobs\SendSMSJob::dispatch($phone, $message);

                // Send Booking SMS to SuperAdmin
                $adminMessage = "New booking alert! " .
                    "Booking #: #{$booking->booking_number}. " .
                    "Customer: {$customer->name} ({$customer->phone}). " .
                    "Address: {$booking->address}. " .
                    "Scheduled for: " .
                    Carbon::parse($booking->scheduled_date)->format('d M Y') . " at " .
                    Carbon::parse($booking->scheduled_time)->format('h:i A') .
                    ". Please check the admin panel for details.";
                $adminUser = User::where('role', 'super_admin')->first();
                if ($adminUser) {
                    $admin_phone = $adminUser->country_code . $adminUser->phone;
                    // \App\Jobs\SendSMSJob::dispatch($admin_phone, $adminMessage);
                }

                // Send booking notification
                $notificationData = [
                    'title' => "Booking Confirmed!",
                    'message' => "Your car wash has been successfully booked for " . Carbon::parse($booking->scheduled_date)->format('d M Y') . " at " . Carbon::parse($booking->scheduled_time)->format('h:i A') . ". Cleaner details will be shared shortly.",
                    'type' => 'booking',
                    'payload' => [
                        'booking_id' => $booking->id,
                        'booking_number' => $booking->booking_number,
                        'customer_id' => $booking->customer_id,
                    ],
                ];
                // $this->save_notification($booking->customer_id, $notificationData);

                $paymentNotification = [
                    'title' => "Payment Received!",
                    'message' => "We've received your payment of £" . $booking->total_amount . " for your recent car wash. Thank you!",
                    'type' => 'payment',
                    'payload' => [
                        'booking_id' => $booking->id,
                        'booking_number' => $booking->booking_number,
                        'customer_id' => $booking->customer_id,
                    ],
                ];
                // $this->save_notification($booking->customer_id, $paymentNotification);

                // Send payment mail
                $paymentData = [
                    'customer_name' => $customer->name,
                    'to_email' => $customer->email,
                    'booking_data' => $booking,
                    'payment_data' => $payment,
                    '_blade' => 'payment-confirm',
                    'subject' => '💳 Payment Received'
                ];
                // \App\Jobs\SendMailJob::dispatch($paymentData);

                // Send booking mail
                $emailData = [
                    'customer_name' => $customer->name,
                    'to_email' => $customer->email,
                    'booking_data' => $booking,
                    '_blade' => 'booking',
                    'subject' => '✅ Booking Confirmed!'
                ];
                // \App\Jobs\SendMailJob::dispatch($emailData);

                Session::flash('success', 'Booking created successfully!');

                return response()->json([
                    'success' => true,
                    'message' => 'Booking created successfully!',
                    'booking_id' => $booking->id,
                    'redirect_url' => route('bookings.index')
                ]);

            } else {
                DB::rollBack();
                throw new Exception('Failed to create booking.');
            }

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the booking. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Generate a unique booking number
    */
    private function generateUniqueOrderNumber()
    {
        do {
            // Generate booking number format: BK + YYYYMMDD + XXXX (4 random digits)
            $bookingNumber = 'BK' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Booking::where('booking_number', $bookingNumber)->exists());

        return $bookingNumber;
    }


    public function getCustomerVehicles($id){
        $vehicles = Vehicle::where('customer_id', $id)->get(['id', 'model', 'license_plate']); // Adjust fields as needed
        return response()->json($vehicles);
    }

    public function validateCoupon(Request $request) {
        $coupon = Coupon::find($request->coupon_id);
        if (!is_null($coupon->user_ids)) {
            $userIds = json_decode($coupon->user_ids, true) ?? [];
            if (!in_array($request->customer_id, $userIds)) {
                return response()->json(['success' => false, 'message' => 'Coupon is not active or expired.']);
            }
        }

        elseif (!is_null($coupon->zipcodes)) {
            $zipcodes = json_decode($coupon->zipcodes, true) ?? [];
            if (!in_array($request->zipcode, $zipcodes)) {
                return response()->json(['success' => false, 'message' => 'Coupon is not active or expired.']);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'type' => $coupon->discount_type,     // 'fixed' or 'percentage'
                'value' => $coupon->discount_value,
                'id' => $coupon->id,
                'code' => $coupon->code
            ]
        ]);
    }
    public function show($id){
        $bookingDetails = Booking::with(['customer','payment','washType'])->where('id',$id)->first();
        if(!$bookingDetails){
            abort(404);
        }
        $this->data['pageTitle'] = 'Bookings Details';
        $this->data['bookingDetails'] = $bookingDetails;
        return view('admin.bookings.show',$this->data);
    }

    public function invoice($id){
        $bookingDetails = Booking::with(['customer','payment','washType'])->where('id',$id)->first();
        if(!$bookingDetails){
            abort(404);
        }
        $this->data['pageTitle'] = 'Booking Invoice';
        $this->data['bookingDetails'] = $bookingDetails;
        return view('admin.bookings.invoice',$this->data);
    }

    public function destroy($id){
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return response()->json(['success' => true, 'message' => 'Booking deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function availableCleaners(Booking $booking){
        $slotDate = \Carbon\Carbon::parse($booking->scheduled_date)->format('Y-m-d');
        $slotTime = \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i:s');
        $cleaners = User::role('cleaner')
        ->whereDoesntHave('bookings', function ($q) use ($slotDate, $slotTime) {
            $q->where('scheduled_date', $slotDate)
            ->where('scheduled_time', $slotTime)
            ->whereIn('status', ['in_progress', 'accepted', 'mark_as_arrived', 'in_route']);
        })
        ->whereDoesntHave('bookingCancellations', function ($q) use ($booking) {
            $q->where('booking_id', $booking->id);
        })
        ->where('is_available',1)
        ->get(['id', 'name']);
        return response()->json($cleaners);
    }

    public function assignBooking(Request $request){
        
        $booking = Booking::findOrFail($request->booking_id);
        $booking->cleaner_id = $request->cleaner_id;
        $booking->save();

        //Send notification to cleaner
        $notificationData = [
            'title' => "New Car Wash Assigned",
            "message" =>  "A new car wash job has been assigned. Check your app for location and time.",
            'type' => 'booking',
            'payload' => [
                'booking_id' => $booking->id,
                'cleaner_id' => $request->cleaner_id,
            ],

        ];
        // $this->save_notification($request->cleaner_id,$notificationData);
        
        Session::flash('success', "Cleaner assigned successfully");
        return redirect()->route('bookings.index');
        
    }

    public function cancelBooking($id){

        $booking = Booking::findOrFail($id );
        $booking->update(['status' => 'cancelled']);

        //Send notification to customer
        $notificationData = [
            'title' => "Booking Cancelled",
            "message" =>  "Your booking on ".$booking->scheduled_date." has been cancelled. You can rebook anytime from the app.",
            'type' => 'booking',
            'payload' => [
                'booking_id' => $booking->id,
                'cleaner_id' => $booking->cleaner_id ?? null,
                'customer_id' => $booking->customer_id,
            ],

        ];
        // $this->save_notification($booking->customer_id,$notificationData);

        if($booking->cleaner_id){

            //Send notification to cleaner
            $notificationData = [
                'title' => "Job Canceled",
                "message" =>  "Your scheduled job on ".$booking->scheduled_date." has been canceled by the customer.",
                'type' => 'booking',
                'payload' => [
                    'booking_id' => $booking->id,
                    'cleaner_id' => $booking->cleaner_id,
                    'customer_id' => $booking->customer_id,
                ],
    
            ];
            // $this->save_notification($booking->cleaner_id,$notificationData);
        }
        Session::flash('success', "Booking canceled successfully");
        return response()->json(['success' => true, 'message' => 'Booking canceled successfully.']);
    }

    public function searchVehicle(Request $request)
    {
        $vehicleNumber = $request->input('number');
        if (empty($vehicleNumber)) {
            return response()->json(['success' => false, 'message' => 'Vehicle number is required'], 400);
        }

        $vehicleNumber = $request->vehicle_number;
        $apikey = env('APP_ENV') == "local" ? config('constants.CAR_CHECK_TEST_API_KEY') : config('constants.CAR_CHECK_LIVE_API_KEY');
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.checkcardetails.co.uk/vehicledata/vehicleregistration?apikey='.$apikey.'&vrm='.$vehicleNumber,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $apiResponse = curl_exec($curl);

        curl_close($curl);
//         $apiResponse = '{
//     "registrationNumber": "EA65AMX",
//     "make": "AUDI",
//     "model": "A7",
//     "colour": "BLACK",
//     "fuelType": "DIESEL",
//     "engineCapacity": 2967,
//     "yearOfManufacture": 2015,
//     "vehicleAge": "9 years 9 months",
//     "wheelplan": "2 AXLE RIGID BODY",
//     "dateOfLastV5CIssued": "2024-09-25",
//     "typeApproval": "M1",
//     "co2Emissions": 142,
//     "registrationPlace": "Chelmsford",
//     "tax": {
//         "taxStatus": "Taxed",
//         "taxDueDate": "2026-08-01",
//         "days": "366"
//     },
//     "mot": {
//         "motStatus": "Valid",
//         "motDueDate": "2026-01-26",
//         "days": 179
//     }
// }';
        $response = json_decode($apiResponse, true);
        $result = [];
        if (isset($response['registrationNumber'])) {
            // Success case
            $data = $response;
        
            $result = [
                'Colour' => $data['colour'] ?? null,
                'Vrm' => $data['registrationNumber'] ?? null,
                'Make' => $data['make'] ?? null,
                'Model' => $data['model'] ?? null,
                'YearOfManufacture' => $data['yearOfManufacture'] ?? null,
                'VehicleClass' => $data['VehicleClass'] ?? null,
            ];
        
        }
        if(empty($result)){
            return response()->json(['data' => [],'error'=> ''],200);
        }
        return response()->json(['success' => true, 'data' => $result]);
    }
}
