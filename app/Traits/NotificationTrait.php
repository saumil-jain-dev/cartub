<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Google\Client as GoogleClient;

trait NotificationTrait
{
    # Send Push Notification (Android & Ios)
//     public static function push_notification($to, $title, $message, $extra = null)
//     {
// Log::info("message",[is_array($to)]);

//         $firebaseUrl = config('app.FIREBASE_URL');
//         $fcmKey = config('app.FIREBASE_SERVER_KEY');

//         $headers = array(
//             'Authorization: key=' . $fcmKey,
//             'Content-Type: application/json'
//         );

//         $fields = array(
//             'to' => $to,
//             'notification' => [
//                 'title' => $title,
//                 'body' => $message,
//             ],
//             'data' => $extra ?? null
//         );

//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
//         curl_setopt($ch, CURLOPT_URL, $firebaseUrl);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//         $result = curl_exec($ch);
//         curl_close($ch);

//         $resultArr = json_decode($result, true);
        
//         if (empty($resultArr)) {
//             return false;
//         }

//         if ($resultArr['success'] == 1 && $resultArr['success'] && isset($resultArr) && isset($resultArr['success']))  {
//             return true;
//         }

//         return false;
//     }
    public static function push_notification($to, $title, $message, $extra = null)
    {
        Log::info('Sending notification', ['to' => is_array($to) ? implode(', ', $to) : $to]);
    
        $firebaseUrl = config('constants.FIREBASE_URL');
        $fcmKey = config('constants.FIREBASE_SERVER_KEY');
        $projectId = config('constants.FCM_PROJECT_ID');
        $credentialsFilePath = public_path('cartub-ee854-firebase-adminsdk-fbsvc-d11b60de14.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];
        
    
        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];
    
    
        $fields = [
            'message' => [
                'token' => $to,
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'data' => $extra
            ]
        ];
   
        $payload = json_encode($fields);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/".config('constants.FCM_PROJECT_ID')."/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $result = curl_exec($ch);
        
        if ($result === false) {
            
            Log::error('cURL error: ' . curl_error($ch));
            curl_close($ch);
            return false; 
        }
        
        curl_close($ch);
        
        
        $resultArr = json_decode($result, true);
        
        Log::info('FCM Response', ['response' => $resultArr]);
        
        
        if (isset($resultArr['name'])) {
            
            return true;
        }
        
        
        if (isset($resultArr['error'])) {
            Log::error('FCM error', [
                'code' => $resultArr['error']['code'] ?? 'Unknown Code',
                'message' => $resultArr['error']['message'] ?? 'Unknown Error',
                'status' => $resultArr['error']['status'] ?? 'Unknown Status',
            ]);
        }
        
        return false;
    }

    public static function save_notification($receiverId, $notification)
    {
        $user = User::find($receiverId);
        $send_notification = false;
        if ($user && $user->devices && $user->devices->device_token) {
            $send_notification = self::push_notification($user->devices->device_token, $notification['title'], $notification['message'], $notification['payload']);
        }
        if ($send_notification) {
            $notificationModel = new Notification();
            $notificationModel->user_id = $receiverId;
            $notificationModel->title = $notification['title'];
            $notificationModel->message = $notification['message'];
            $notificationModel->type = $notification['type'];
            $notificationModel->payload = json_encode($notification)['payload'] ?? null;
            $notificationModel->save();
        }
    }
}