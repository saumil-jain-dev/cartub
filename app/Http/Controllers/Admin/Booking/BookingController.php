<?php

namespace App\Http\Controllers\Admin\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
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
}
