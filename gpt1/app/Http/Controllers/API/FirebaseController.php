<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\SystemLog;

class FirebaseController extends Controller
{
    /**
     * This should name with the meaning of what it does.
     */
    public function executeOrderDetail($record_id)
    {
        try {
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/serviceAccountKey.json');
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withDatabaseUri('https://gangos-cambodia.firebaseio.com')
                ->create();
            $database = $firebase->getDatabase();
            $newPost = $database->getReference('call/' . $record_id);
            return  $newPost->getValue();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $errorMessage = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return $errorMessage;
        }
    }


    /**
     * push notification to seller phone via Firebase.
     */
    public function pushNotificatoin(
        $orderId,
        $tokenList,
        $device_type,
        $order_flag = 1,
        $body,
        $title,
        $to_user,
        $notification_type,
        $notification_action
    ) {
        try {
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

            // Preparing data
            $data = [
                "body" => $body,
                'title' => $title,
                "order_id" => $orderId,
                "to_user" => $to_user,
                "notification_type" => $notification_type,
                "notification_action" => $notification_action,
                "click_action" => '/notifications/orders/' . $orderId,
            ];

            // add data depending on realtime database notification or messaging
            if ($order_flag == 1) {
                $data += ['is_ring' => true, 'call-from' => Auth()->user()->name];
            } else {
                $data += ['sound' => true, 'is_ring' => false];
            }

            $fcmNotification = [
                'registration_ids' => $tokenList,
                'data' => $data,
                'notification' => $data,
                /*
                'notification' => [
                    "title" => env('APP_NAME'),
                    "body" => 'New order #' . $orderId . '. Tap to vew order!',
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    "content_available" =>  true
                ],
                */
            ];

            if ($device_type == "ios") {
                $data += ['content_available' => true];
                $fcmNotification += ["notification" => $data];
            }

            $firebaseKey = env("FIREBASE_KEY", null);
            $headers = [
                'Authorization: key=' . $firebaseKey,
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            curl_close($ch);

            // There should be a way to return proper message. BTY
            $response = [
                'status' => true,
                'msg' => __('orders.notification_sent'),
                'data' => [
                    'notification' =>  $result,
                    'logs' => $fcmNotification
                ],
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $errorMessage = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return $errorMessage;
        }
    } //EOF

    /**
     * Update record on Firebase server
     */
    public function updateFirebase($recordId, $receiver, $isRing)
    {
        try {
            $serviceAccountKeyFileName = env("SERVICE_ACCOUNT_KEY_FILE_NAME", null);
            $realTimeDbUrl = env("REAL_TIME_DB_URL", null);
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/' . $serviceAccountKeyFileName);
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withDatabaseUri($realTimeDbUrl)
                ->create();
            $database = $firebase->getDatabase();

            $database->getReference('call/' . $recordId)
                ->update([
                    'accept_by' => $receiver,
                    'is_ring' => $isRing
                ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $errorMessage = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return $errorMessage;
        }
    } // EOF

    /**
     * Save record on Firebase server
     */
    public function saveFirebase($record_id, $caller)
    {
        try {
            $serviceAccountKeyFileName = env("SERVICE_ACCOUNT_KEY_FILE_NAME", null);
            $realTimeDbUrl = env("REAL_TIME_DB_URL", null);
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/' . $serviceAccountKeyFileName);
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withDatabaseUri($realTimeDbUrl)
                ->create();
            $database = $firebase->getDatabase();

            $database->getReference('call/' . $record_id)
                ->set([
                    'accept_by' => '', 'call_from' => $caller,
                    'is_ring' => true, 'request_id' => $record_id
                ]); //save or update specific record ($record_id)

            return true;
        } catch (\Exception $e) {
            $errorMessage = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return $errorMessage;
        }
    } // EOF
}
