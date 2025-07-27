<?php

use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;


/**
 * Check if logged-in user has given permission.
 * Super Admin always has all permissions.
 *
 * @param string $permission
 * @return bool
 */
function hasPermission(string $permission): bool
{
    $user = Auth::user();

    if (! $user) {
        return false;
    }

    // If user is super_admin, always true
    if ($user->hasRole('super_admin')) {
        return true;
    }

    return $user->can($permission);
}

function sendSMS(string $phone, string $message): bool{

    try{
        $key = config('constants.SMS_API_KEY');
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.voodoosms.com/sendsms',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'to' => $phone,
                'from' => 'CarTub',
                'msg' => $message,
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $key,
            ],
        ]);
    
        $response = curl_exec($curl);
    
        curl_close($curl);
    
        curl_close($curl);
        $response = json_decode($response, true);
        if(isset($response['messages']) && $response['messages'] != ''){
            if($response['messages']['status'] == "PENDING_SENT"){
                return true;
            }
        }
        return false;
    } catch(Exception $e){
        Log::error($e->getMessage());
        return false;
    }

}
function getSettingsData($key){
    $settingData = Setting::where("key","=",$key)->first();
    if(isset($settingData)){
        return $settingData->value;
    }
    return false;
}

