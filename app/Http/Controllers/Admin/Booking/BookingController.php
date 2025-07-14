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

        if(! hasPermission('roles-permission.index')){
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
     
        $this->data['pageTitle'] = 'Bookings Details';
        $this->data['bookingDetails'] = Booking::with(['customer','payment','washType'])->where('id',$id)->first();
        return view('admin.bookings.show',$this->data);
    }
}
