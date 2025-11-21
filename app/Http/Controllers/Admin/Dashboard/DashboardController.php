<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CleanerEarning;
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
        $total_revenue = Booking::get()->sum('total_amount');
        $total_commission = CleanerEarning::get()->sum('amount');
        $this->data['pageTitle'] = 'Dashboard';
        $this->data['total_booking_count'] = Booking::get()->count();
        $this->data['total_revenue'] = $total_revenue;
        $this->data['total_active_customer'] = User::where('is_active',1)->where('role','customer')->count();
        $this->data['total_active_cleaner'] = User::where('is_active',1)->where('role','cleaner')->count();
        $this->data['live_wash_data'] = Booking::where('status','pending')->whereNull('cleaner_id')->with('vehicle')->orderBy('id','desc')->get();
        $this->data['washTypes'] = $washTypes;
        $this->data['total_commission'] = $total_commission;
        $this->data['total_amount'] = $total_revenue - $total_commission;
        return view('admin.dashboard',$this->data); // Ensure this view exists
    }

    public function filter(Request $request){
        $start = $request->start ? $request->start . ' 00:00:00' : null;
        $end   = $request->end ? $request->end . ' 23:59:59' : null;
        $reset = $request->reset;

        if ($reset) {
            // No filtering
            $bookings = Booking::query();
            $cleanerEarnings = CleanerEarning::query();
            $customers = User::where('role', 'customer');
            $cleaners = User::where('role', 'cleaner');

        } else {
            // Filter by date range
            $bookings = Booking::when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            });
            
            $cleanerEarnings = CleanerEarning::when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            });

            // NEW — Filter customers & cleaners by registration date
            $customers = User::where('role', 'customer')
                ->when($start && $end, function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                });

            $cleaners = User::where('role', 'cleaner')
                ->when($start && $end, function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                });

        }
        
        return response()->json([
            'total_booking_count'   => $bookings->count(),
            'total_revenue'         => $bookings->sum('total_amount'),
            'total_active_customer' => $customers->count(),   // filtered ✔
            'total_active_cleaner'  => $cleaners->count(),    // filtered ✔
            'total_commission'      => $cleanerEarnings->sum('amount'),
            'total_amount'          => $bookings->sum('total_amount') - $cleanerEarnings->sum('amount'),
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

    public function metrics(Request $request)
    {
        $washTypes = Service::where('type', 'service')
        ->withCount('bookings')   // assumes you have bookings() relationship
        ->orderByDesc('bookings_count')
        ->get();

        $total_revenue = Booking::get()->sum('total_amount');
        $total_commission = CleanerEarning::get()->sum('amount');

        $totalBookings = Booking::get()->count();
        $totalRevenue = Booking::get()->sum('total_amount');
        $activeCustomers = User::where('is_active',1)->where('role','customer')->count();
        $activeCleaners = User::where('is_active',1)->where('role','cleaner')->count();
        $totalAmount = $total_revenue - $total_commission;

        $liveWashData = Booking::where('status','pending')->whereNull('cleaner_id')->with(['vehicle','customer','cleaner'])->orderBy('id','desc')->get()->map(function($b) {
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
                    'booking_number' => $b->booking_number,
                    'booking_date' => $b->created_at->format('d-m-Y'),
                    'schedule_date' => \Carbon\Carbon::parse($b->scheduled_date)->format('d-m-Y'),
                ];
            });
        $this->data['washTypes'] = $washTypes;
        return response()->json([
            'total_booking_count'    => $totalBookings,
            'total_revenue'          => $totalRevenue,
            'total_active_customer'  => $activeCustomers,
            'total_active_cleaner'   => $activeCleaners,
            'total_amount'           => $totalAmount,
            'total_commission'       => $total_commission,
            'live_wash_data'         => $liveWashData,
            'wash_types'             => $washTypes,   // if you want to refresh that table too
        ]);
    }
}
