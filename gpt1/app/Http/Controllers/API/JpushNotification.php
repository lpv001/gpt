<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JpushNotification
{
    public function jPushNoti($orderId, $tokenList, $orderFlag = 1, $body, $title, $isRing,
                              $callFrom, $acceptBy, $toUser, $notificationType, $notificationAction) {
        try {
            $jPushUrl = 'https://api.jpush.cn/v3/push';

            // Preparing data
            $headers = [
                'authorization: Basic ZmM5NWY4YTQyNjk4MDAzYTJjZjNiNDlhOjQ0YzA2YjVkYmE2ZDliY2ZlNzYwNTYxOQ==',
                'content-type: application/json'
            ];

            $extras = [
                "order_id" => $orderId,
                "body" => $body,
                "call_from" => $callFrom,
                "acceptBy" => $acceptBy,
                "alert" => $title,
                "title" => $title,
                "content_available" => true,
                "to_user" => $toUser,
                "notification_type" => $notificationType,
                "notification_action" => $notificationAction
            ];

            // add data depending on realtime database notification or messaging
           if ($orderFlag == 2) {
                $extras += ['sound' => true, 'is_ring' => false, 'is_seller' => true];
           } else {
                $extras += ['is_ring' => $isRing, "is_seller" => false];
           }

            $notification = [
                "android" => [
                    "alert" => "",
                    "extras" => $extras
                ],
                "ios" => [
                    "alert" => $body,
                    "extras" => $extras
                ],

            ];
            //$tokenList = ["18071adc0364db238e6"];
            $jPushNotification = [
                "platform" => "all",
                "audience" => [
                    'registration_id' => $tokenList
                ],
                "notification"=> $notification,
                "options" => [
                    "apns_production" => false
                ]
            ];

//            if($device_type == "ios") {
//                $notification += ['content_available' => true];
//                $jPushNotification += ["notification" => $notification];
//            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$jPushUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jPushNotification));
            $result = curl_exec($ch);
            curl_close($ch);

            // There should be a way to return proper message. BTY
            $response = [
                'status' => true,
                'msg' => __('orders.notification_sent'),
                'message' => [
                    'kh' => "Push notification successfully",
                    'en' => "Push notification successfully",
                    'ch' => "Push notification successfully"
                ],
                'data' => [
                    'notification' =>  $result,
                    'logs' => $jPushNotification
                ],
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = [
                'status' => false,
                'message' => [
                    'kh' => $e->getMessage(),
                    'en' => $e->getMessage(),
                    'ch' => $e->getMessage(),
                ],
                'data' => [],
            ];
            return $errorMessage;
        }
    }
}
