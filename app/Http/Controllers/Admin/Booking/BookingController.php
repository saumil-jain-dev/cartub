<?php

namespace App\Http\Controllers\Admin\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use App\Traits\NotificationTrait;

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
}
