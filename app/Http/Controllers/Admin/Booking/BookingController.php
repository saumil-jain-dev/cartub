<?php

namespace App\Http\Controllers\Admin\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    //
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
}
