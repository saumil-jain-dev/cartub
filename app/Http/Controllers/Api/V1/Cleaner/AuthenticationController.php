<?php

namespace App\Http\Controllers\Api\V1\Cleaner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cleaner\AvailabilityRequest;
use App\Http\Requests\Api\Cleaner\LoginRequest;
use App\Http\Requests\Api\Cleaner\MarkNotificationReadRequest;
use App\Http\Requests\Api\Cleaner\ProfileUpdateRequest;
use App\Http\Requests\Api\Cleaner\RegisterRequest;
use App\Http\Requests\Api\Cleaner\UpdateLocationRequest;
use App\Http\Resources\Api\Cleaner\NotificationListResource;
use App\Http\Resources\Api\Cleaner\PaymentListResource;
use App\Http\Resources\Api\Cleaner\RegisterResource;
use App\Http\Resources\Api\Cleaner\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Api\Cleaner\AuthService;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    //
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        
        try{

            $user = $this->authService->register($request);
            return success(new RegisterResource($user), trans('messages.register_success'), config('code.SUCCESS_CODE'));
        } catch (Exception $e){
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function login(LoginRequest $request)
    {
        try{
            $user = User::where('email', $request->email)->where('role',$request->role)->first();
            if(!Hash::check($request->password, $user->password)){
                
                return success([], trans('messages.invalid_password'), config('code.BAD_REQUEST_CODE'));
            }
            $user = $this->authService->login($request);
            return success(new UserResource($user), trans('messages.login_success'), config('code.SUCCESS_CODE'));
        }
        catch (Exception $e){
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function logout() {
        try {
            $userLogout = $this->authService->logOut();
            return success([], trans('messages.logout_success'), config('code.SUCCESS_CODE'));
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function profile(){
        try {
            $userProfile = $this->authService->profile();
            return success(new UserResource($userProfile), trans('messages.view',['attribute' => 'Profile']), config('code.SUCCESS_CODE'));
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }
    public function updateProfile(ProfileUpdateRequest $request){
        try {
            $userUpdateProfile = $this->authService->updateProfile($request);
            return success(new UserResource($userUpdateProfile), trans('messages.update',['attribute' => 'Profile']), config('code.SUCCESS_CODE'));
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function listNotifications(Request $request)
    {
        try {
            $notifications = $this->authService->listNotifications($request);
            if($notifications){
                return success(
                    pagination(NotificationListResource::class, $notifications),
                    trans('messages.list', ['attribute' => 'Notifications']),
                    config('code.SUCCESS_CODE')
                );
            }
        } catch(Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function markNotificationAsRead(MarkNotificationReadRequest $request){
        try {
            $markNotification = $this->authService->markNotificationAsRead($request);
            return success([], trans('messages.update',['attribute' => 'Notification']), config('code.SUCCESS_CODE'));
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function getDashboardData(Request $request)
    {
        try {
            $dashboardData = $this->authService->getDashboardData($request);
            return success($dashboardData, trans('messages.update',['attribute' => 'Profile']), config('code.SUCCESS_CODE'));
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function updateLocation(UpdateLocationRequest $request)
    {
        try {
            $updateLocation = $this->authService->updateLocation($request);
            return success(
                $updateLocation,trans('messages.update', ['attribute' => 'Location']),
                config('code.SUCCESS_CODE')
            );
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function listPaymentHistory(Request $request)
    {
        try {
            
            $paymentHistory = $this->authService->listPaymentHistory($request);
            if($paymentHistory){
                return success(
                    pagination(PaymentListResource::class, $paymentHistory),
                    trans('messages.list', ['attribute' => 'Payment History']),
                    config('code.SUCCESS_CODE')
                );
            }
        } catch(Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function updateAvailability(AvailabilityRequest $request){
        try {
            $updateLocation = $this->authService->updateAvailability($request);
            return success(
                $updateLocation,trans('messages.update', ['attribute' => 'Availability']),
                config('code.SUCCESS_CODE')
            );
        } catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }
}
