<?php

namespace App\Services\Api\Cleaner;

use App\Http\Resources\Api\Auth\LoginRegisterResource;
use App\Http\Resources\Api\Auth\VerificationResource;
use App\Models\Feedback;
use App\Models\HelpCenter;
use App\Models\Notification;
use App\Models\NotificationReceiver;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserDevice;
use Exception;
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
}
