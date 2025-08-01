<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
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
        $washTypes = Service::where('type', 'service')
        ->withCount('bookings')   // assumes you have bookings() relationship
        ->orderByDesc('bookings_count')
        ->get();

        $this->data['pageTitle'] = 'Dashboard';
        $this->data['total_booking_count'] = Booking::get()->count();
        $this->data['total_revenue'] = Booking::get()->sum('total_amount');
        $this->data['total_active_customer'] = User::where('is_active',1)->where('role','customer')->count();
        $this->data['total_active_cleaner'] = User::where('is_active',1)->where('role','cleaner')->count();
        $this->data['live_wash_data'] = Booking::with('vehicle')->orderBy('id','desc')->get();
        $this->data['washTypes'] = $washTypes;
        return view('admin.dashboard',$this->data); // Ensure this view exists
    }

    public function bookingTrend(Request $request)
    {
        $filter = $request->get('filter', 'week');

        $data = match ($filter) {
            'week' => $this->getWeeklyData(),
            'month' => $this->getMonthlyData(),
            'prev_month' => $this->getPreviousMonthData(),
            'last_3_months' => $this->getLast3MonthsData(),
            default => $this->getWeeklyData(),
        };

        return response()->json([
            'data' => $data,
        ]);
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
