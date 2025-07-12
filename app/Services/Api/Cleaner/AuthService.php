<?php

namespace App\Services\Api\Cleaner;

use App\Http\Resources\Api\Auth\LoginRegisterResource;
use App\Http\Resources\Api\Auth\VerificationResource;
use App\Jobs\Customer\SendMailJob;
use App\Models\Booking;
use App\Models\BookingCancellation;
use App\Models\CleanerEarning;
use App\Models\CleanerLocation;
use App\Models\Feedback;
use App\Models\HelpCenter;
use App\Models\Notification;
use App\Models\NotificationReceiver;
use App\Models\Rating;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserDevice;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Catch_;

class AuthService {

    public function register($request){
        
        DB::beginTransaction();
        try{
            $user = User::create($request->all());
            $user->assignRole($request->role);
            DB::commit();
            return $user;

        }catch(Exception $e){
            DB::rollBack();
            throw new Exception('Failed to register customer: ' . $e->getMessage());
        }
    }

    public function login($request){
        
        DB::beginTransaction();
        try{
            $user = User::where('email', $request->email)->where('role',$request->role)->first();
            
            Auth::login($user);
            $token = $user->createToken('token-name')->plainTextToken;
            $user->access_token = $token; // Store the token in the user model

            UserDevice::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'device_id' => $request->device_id,
                    'device_type' => $request->device_type,
                    'device_token' => $request->device_token,
                    'os_version' => $request->os_version,
                    'app_version' => $request->app_version,
                    'device_name' => $request->device_name,
                    'model_name' => $request->model_name,
                    'status' => 1
                ]
            );
            DB::commit();
            return $user;

        }catch(Exception $e){
            DB::rollBack();
            throw new Exception('Failed to login: ' . $e->getMessage());
        }
    }
    
    public function logOut(){
        $user = Auth::user();
        DB::table('personal_access_tokens')
        ->where('tokenable_id', $user->id)
        ->delete();
        UserDevice::where('user_id',$user->id)->delete();

        return true;
    }

    public function profile(){
        $user = Auth::user();
        return $user;
    }

    public function updateProfile($request){
        DB::beginTransaction();
        try{
            $user = Auth::user();
            $profile_picture = $user->profile_picture;
            $data = $request->all();
            if($request->hasFile('profile_picture')){
                $profile_picture = uploadImage($request->file('profile_picture'),'profile_picture/'.$user->id);
            }
            $data['profile_picture'] = $profile_picture;
            $user->update($data);
            
            DB::commit();

            //Send profile update mail
            $profileData = [
                'customer_name' => $user->name,
                'to_email' => $user->email,
                'user_data' => $user,
                '_blade' => 'profile-update',
                'subject' => 'Profile Information Updated 🔐'
            ];
            SendMailJob::dispatch($profileData);
            return $user;

        }catch(Exception $e){
            DB::rollBack();
            throw new Exception('Failed to update profile: ' . $e->getMessage());
        }
    }
    public function listNotifications($request){
        try {
            $perPage = $request->input('per_page', 10);
            $user = Auth::user();
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)->withQueryString();
            return $notifications;

        } catch (Exception $e) {
            throw new Exception('Failed to list notifications: ' . $e->getMessage());
        }
    }

    public function markNotificationAsRead($request){
        DB::beginTransaction();
        try {
            $ids = array_filter(array_map('trim', explode(',', $request->notification_ids)));
            $notification = Notification::whereIn('id', $ids)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true]);
            
            DB::commit();
            return $notification;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to mark notification as read: ' . $e->getMessage());
        }
    }

    public function getDashboardData($request){
        try {
            $cleaner = Auth::user();
            $today = Carbon::today();
            $weekStart = Carbon::now()->startOfWeek();
            $monthStart = Carbon::now()->startOfMonth();
            $bookingsQuery = Booking::where('cleaner_id', $cleaner->id)
            ->whereIn('status', ['completed']);
            
            $todayBookings = (clone $bookingsQuery)->whereDate('created_at', $today)->get();
            $weekBookings  = (clone $bookingsQuery)->whereBetween('created_at', [$weekStart, Carbon::now()])->get();
            $monthBookings = (clone $bookingsQuery)->whereBetween('created_at', [$monthStart, Carbon::now()])->get();

            $earningsQuery = CleanerEarning::where('cleaner_id', $cleaner->id);
            $todayEarnings  = (clone $earningsQuery)->whereDate('earned_on', $today)->get();
            $weekEarnings   = (clone $earningsQuery)->whereBetween('earned_on', [$weekStart, now()])->get();
            $monthEarnings  = (clone $earningsQuery)->whereBetween('earned_on', [$monthStart, now()])->get();

            return [
                'today' => [
                    'wash_count' => $todayBookings->count(),
                    'earning' => $todayEarnings->sum(fn ($e) => $e->amount + $e->tip),
                ],
                'week' => [
                    'wash_count' => $weekBookings->count(),
                    'earning' => $weekEarnings->sum(fn ($e) => $e->amount + $e->tip),
                ],
                'month' => [
                    'wash_count' => $monthBookings->count(),
                    'earning' => $monthEarnings->sum(fn ($e) => $e->amount + $e->tip),
                ],
                'performance' => $this->getPerformanceReport($cleaner, $bookingsQuery),
                'earnings_report' => $this->getEarningsReport($cleaner, $earningsQuery, $bookingsQuery),
                'average_rating' => $this->getAverageRating($cleaner),
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to get dashboard data: ' . $e->getMessage());
        }
    }

    protected function getPerformanceReport($cleaner, $bookingsQuery)
    {
        $allBookings = $bookingsQuery->get();

        $customerIds = $allBookings->pluck('customer_id')->unique();
        $repeatCustomers = $allBookings->groupBy('customer_id')->filter(fn($g) => $g->count() > 1)->count();

        $totalDuration = $allBookings->reduce(function ($carry, $booking) {
            if ($booking->job_start_time && $booking->job_end_time) {
                $start = \Carbon\Carbon::parse($booking->job_start_time);
                $end = \Carbon\Carbon::parse($booking->job_end_time);
                $carry += $start->diffInMinutes($end);
            }
            return $carry;
        }, 0);

        $avgWashTime = $allBookings->count() ? round($totalDuration / $allBookings->count(), 1) : 0;

        $cancellations = BookingCancellation::where('cleaner_id',$cleaner->id)->count();

        return [
            'total_customers' => $customerIds->count(),
            'repeat_customers' => $repeatCustomers,
            'average_wash_time_minutes' => $avgWashTime,
            'cancellations' => $cancellations,
        ];
    }

    protected function getEarningsReport($cleaner, $earningsQuery, $bookingsQuery)
    {
        $allEarnings = $earningsQuery->get();

        $totalEarning = $allEarnings->sum('amount') + $allEarnings->sum('tip');
        $totalTip     = $allEarnings->sum('tip');
        $avgPayment   = $allEarnings->count() ? round($totalEarning / $allEarnings->count(), 2) : 0;

        $washType = $bookingsQuery->get()->groupBy('wash_type_id')
            ->sortByDesc(fn ($g) => $g->count())
            ->keys()
            ->first();

        $topWashType = optional(optional($bookingsQuery->get()->firstWhere('wash_type_id', $washType))->washType)->name ?? null;

        return [
            'total_earning' => $totalEarning,
            'total_tip' => $totalTip,
            'average_payment_per_wash' => $avgPayment,
            'top_wash_type' => $topWashType,
        ];
    }

    protected function getAverageRating($cleaner)
    {
        return (float)round(
            Rating::where('cleaner_id', $cleaner->id)->avg('rating') ?? 0,
            2
        );
    }
    public function updateLocation($request)
    {
        try {
            
            $cleaner = CleanerLocation::updateOrCreate(
                ['cleaner_id' => Auth::id()],
                ['latitude' => $request->input('latitude'), 'longitude' => $request->input('longitude')]
            );
            

            return $cleaner;
        } catch (Exception $e) {
            throw new Exception('Error updating location: ' . $e->getMessage());
        }
    }

    public function listPaymentHistory($request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $user = Auth::user();
            $paymentHistory = CleanerEarning::with('booking')->where('cleaner_id', $user->id)
                ->orderBy('booking_id', 'desc')
                ->paginate($perPage)->withQueryString();
            return $paymentHistory;

        } catch (Exception $e) {
            throw new Exception('Failed to list payment history: ' . $e->getMessage());
        }
    }

    public function updateAvailability($request){
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user->update(['is_available' => $request->input('is_available')]);

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update availability: ' . $e->getMessage());
        }
    }
}
