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

    public function metrics(Request $request)
    {
        $washTypes = Service::where('type', 'service')
        ->withCount('bookings')   // assumes you have bookings() relationship
        ->orderByDesc('bookings_count')
        ->get();

        
        $totalBookings = Booking::get()->count();
        $totalRevenue = Booking::get()->sum('total_amount');
        $activeCustomers = User::where('is_active',1)->where('role','customer')->count();
        $activeCleaners = User::where('is_active',1)->where('role','cleaner')->count();
        $liveWashData = Booking::with(['vehicle','customer','cleaner'])->orderBy('id','desc')->get()->map(function($b) {
                if ($b->status === 'pending' && $b->cleaner_id) {
                    $badgeText = 'Assigned';
                    
                } else {
                    switch ($b->status) {
                        case 'pending':
                            $badgeText = 'Pending';
                            break;
                        case 'in_route':
                            $badgeText = 'In Route';
                            break;
                        case 'in_progress':
                            $badgeText = 'In Progress';
                            break;
                        case 'completed':
                            $badgeText = 'Completed';
                            break;
                        case 'cancelled':
                            $badgeText = 'Cancelled';
                            break;
                        default:
                            $badgeText = ucfirst($b->status);
                    }
                }
                return [
                    'id'            => $b->id,
                    'vehicle'       => ($b->vehicle?->model ?? '') 
                                       . ' (' . ($b->vehicle?->license_plate ?? '') . ')',
                    'customer_name' => $b->customer?->name ?? '',
                    'cleaner_name'  => $b->cleaner?->name  ?? '',
                    'status'        => $badgeText,
                    
                ];
            });
        $this->data['washTypes'] = $washTypes;
        return response()->json([
            'total_booking_count'    => $totalBookings,
            'total_revenue'          => $totalRevenue,
            'total_active_customer'  => $activeCustomers,
            'total_active_cleaner'   => $activeCleaners,
            'live_wash_data'         => $liveWashData,
            'wash_types'             => $washTypes,   // if you want to refresh that table too
        ]);
    }
}
