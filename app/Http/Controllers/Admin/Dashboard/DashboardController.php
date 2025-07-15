<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    //
    public $data;
    /**
     * Show the admin dashboard.
     */
    
    public function index(){
        $this->data['pageTitle'] = 'Dashboard';
        return view('admin.dashboard',$this->data); // Ensure this view exists
    }

    public function todayWash(Request $request){
        if(! hasPermission('dashboard.today-wash')){
            abort(403);
        }
        $this->data['pageTitle'] = "Today's Bookings";
        
        $bookings = Booking::with(['customer', 'payment']);

        // Filters
        
        if ($request->filled('payment_status')) {
            $bookings->where('payment_status',$request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $bookings->whereHas('payment', function ($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        $this->data['bookingData'] = $bookings->whereDate('created_at',today())->orderBy('bookings.id','desc')->get();
        
        return view('admin.today-wash',$this->data);
    }

    public function liveWashStatus(Request $request){
        $this->data['pageTitle'] = "Live Wash Status";
        return view('admin.live-wash-status',$this->data);
    }
    
    public function getByStatus(Request $request)
    {
        $status = $request->status;

        $bookings = Booking::with(['customer', 'cleaner', 'vehicle', 'washType'])
            ->where('status', $status)
            ->get();

        return response()->json(['data' => $bookings]);
    }
}
