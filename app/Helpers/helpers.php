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

// function sendSMS(string $phone, string $message): bool{

//     try{
//         $key = config('constants.SMS_API_KEY');
        
//         $curl = curl_init();
//         curl_setopt_array($curl, [
//             CURLOPT_URL => 'https://api.voodoosms.com/sendsms',
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => '',
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => 'POST',
//             CURLOPT_POSTFIELDS => json_encode([
//                 'to' => $phone,
//                 'from' => 'CarTub',
//                 'msg' => $message,
//             ]),
//             CURLOPT_HTTPHEADER => [
//                 'Content-Type: application/json',
//                 'Authorization: Bearer ' . $key,
//             ],
//         ]);
    
//         $response = curl_exec($curl);
    
//         curl_close($curl);
    
//         curl_close($curl);
//         $response = json_decode($response, true);
//         if(isset($response['messages']) && $response['messages'] != ''){
//             if($response['messages']['status'] == "PENDING_SENT"){
//                 return true;
//             }
//         }
//         return false;
//     } catch(Exception $e){
//         Log::error($e->getMessage());
//         return false;
//     }

// }

function sendSMS(string $phone, string $message): bool
{
    try {
        $key = config('constants.SMS_API_KEY');
        
        // Check if API key exists
        if (empty($key)) {
            Log::error('SMS API key not configured');
            return false;
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.voodoosms.com/sendsms',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, // Increased timeout for better reliability
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
        
        // Check for cURL errors
        if (curl_error($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            Log::error('cURL error in SMS function: ' . $error);
            return false;
        }
        
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl); // Remove duplicate curl_close
        
        // Check HTTP status code
        if ($httpCode !== 200) {
            Log::error('SMS API returned HTTP code: ' . $httpCode . ', Response: ' . $response);
            return false;
        }
        
        $response = json_decode($response, true);
        
        // Check for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to decode SMS API response: ' . json_last_error_msg());
            return false;
        }
        
        // More robust response checking
        if (isset($response['messages']) && is_array($response['messages'])) {
            // Handle both single message and array of messages
            $messages = $response['messages'];
            if (!is_array($messages) || !isset($messages[0])) {
                $messages = [$messages];
            }
            
            foreach ($messages as $msg) {
                if (isset($msg['status']) && $msg['status'] === 'PENDING_SENT') {
                    return true;
                }
            }
        }
        
        // Log the response for debugging if message wasn't sent successfully
        Log::warning('SMS not sent successfully. Response: ' . json_encode($response));
        return false;
        
    } catch (Exception $e) {
        Log::error('SMS function exception: ' . $e->getMessage());
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

