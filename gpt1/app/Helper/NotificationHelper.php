<?php
namespace App\Helper;

use App\Http\Controllers\API\FirebaseController;
use App\Http\Controllers\API\JpushNotification;

use Illuminate\Support\Facades\Auth;

use App\User;
use App\Notification;
use App\Order;
use App\OrderItem;
use App\OrderOption;
use App\Shop;
use App\Point;
use App\OrderLog;
use App\SystemLog;
use App\EventScript;
use App\EventScriptContent;
use App\Role;

class NotificationHelper {
    /**
     *
     */
    public function saveNotification($params)
    {
      $noti = new Notification;
      $noti->user_id = $params['user_id'];
      $noti->action_id = $params['action_id'];
      $noti->type = $params['type'];
      $noti->title = $params['title'];
      $noti->body = $params['body'];
      $noti->save();
      
      return ['status' => true, 'message' => $noti];
    }

    /**
     *
     */
    public function sendNotification($params, $locale, $flag)
    {
        $pushNotification = [];
        $orderFlag = $params['orderFlag'];
        $appIsRing = env('NOTI_IS_RING');
        $app_service_location = env('APP_SERVICE_LOCATION');
        $senderName = Auth::user()->full_name;
        $orderId = $params['orderId'];
        $shopsNearby = $params['shopsNearby'];
        $notificationScript = $params['notificationScript'];
        
        // Firebase does not work in China, use jPush
        if ($app_service_location == 'china') {
            $jPushNoti = new JpushNotification;
        } else {
            $firebase = new FirebaseController;
        }
        
        // Load scripts
        $eventScript = new EventScript;
        $eventScriptContent = new EventScriptContent;
        $eventScriptId = $eventScript->getEventScriptIdByKeyName($notificationScript);
        $scriptContent = $eventScriptContent->getEventScriptContentByEventScriptIdAndLanguage($eventScriptId, $locale);
        $title = $scriptContent->title;
        $body = $scriptContent->body;
        $callFrom = Auth::user()->id;
        
        // Prepare token list
        $tokenListAndroid = [];
        $tokenListIos = [];
        $toUser = \Config::get('constants.notification.to_user.seller');
        $notificationType = \Config::get('constants.notification.notification_type.show');
        $notificationAction = \Config::get('constants.notification.notificatioin_action.execute_order');
        
        //  Check if it is ring on firebase
        if ($app_service_location != 'china' && $appIsRing == true) {
            $firebase->saveFirebase($orderId, $senderName);
            $orderFirebase = $firebase->executeOrderDetail($orderId);
            $notificationType = \Config::get('constants.notification.notification_type.hide');
        }

        // Parsing string
        $jsonReplaceStr = null;
        if (strpos($title, '{{ORDER_ID}}') != false) {
            $title = str_replace("{{ORDER_ID}}", $orderId, $title);
            $jsonReplace = ['ORDER_ID' => $orderId];
            $jsonReplaceStr = json_encode($jsonReplace);
        }
        
        // Build seller list to send notification
        $sellerIdList = [];
        $sellerList = [];
        foreach ($shopsNearby as $shop) {
            foreach ($shop->users as $u) {
                if (!in_array($u->id, $sellerIdList)) {
                    $sellerIdList[] = $u->id;
                    $sellerList[] = $u;
                }
            }
        }
        
        // Get additional mall operators for sending notifications
        $mall_operators = Role::where('slug','mall-operator')->get();
        if (count($mall_operators) > 0) {
          foreach ($mall_operators as $op) {
            foreach ($op->users as $u) {
              if (!in_array($u->id, $sellerIdList)) {
                  $sellerIdList[] = $u->id;
                  $sellerList[] = $u;
              }
            }
          }
        }
        
        // Save & build token list
        foreach ($sellerIdList as $k => $i) {
          $this->saveNotification([
            'user_id' => $sellerList[$k]->id,
            'action_id' => $orderId,
            'type' => 1, //1=NOTI_ORDER
            'title' => $title,
            'body' => $body
          ]);
          
          if ($sellerList[$k]->device_type == "ios") {
              $tokenListIos[] = $sellerList[$k]->fcm_token;
          } else {
              $tokenListAndroid[] = $sellerList[$k]->fcm_token;
          }
        }
        
        // If it is local development, just return here
        if (env('APP_ENV') == 'local') {
          return ['status' => true, 'message' => 'send notification succeed', 'data' => $sellerList];
        }
        
        // Push notification
        if ($app_service_location == 'china') {
            $isRing = true;
            $acceptBy = '';
            if (count($tokenListAndroid) > 0) {
                $pushNotification["android"] = $jPushNoti->jPushNoti($orderId, $tokenListAndroid, $orderFlag, 
                    $body, $title, false, $callFrom, $acceptBy,
                    $toUser, $notificationType, $notificationAction);
            }
            if (count($tokenListIos) > 0) {
                $pushNotification["ios"] = $jPushNoti->jPushNoti($orderId, $tokenListAndroid, $orderFlag, 
                    $body, $title, false, $callFrom, $acceptBy,
                    $toUser, $notificationType, $notificationAction);
            }
        } else {
            if (count($tokenListIos) > 0) {
                $pushNotification["ios"] = $firebase->pushNotificatoin($orderId, $tokenListIos, "ios", $orderFlag, $body, $title,
                $toUser, $notificationType, $notificationAction);
            }
            if (count($tokenListAndroid) > 0) {
                $pushNotification["android"] = $firebase->pushNotificatoin($orderId, $tokenListAndroid, "android", 
                    $orderFlag, $body, $title,
                    $toUser, $notificationType, $notificationAction);
            }
        }
        
        // Debug
        if (env('SYS_LOG') == true) {
            $allParams = [
                'app_service_location' => $app_service_location,
                'orderId' => $orderId,
                'orderFlag' => $orderFlag,
                'callFrom' => $callFrom,
                'toUser' => $toUser,
                'title' => $title,
                'body' => $body,
                'notificationType' => $notificationType,
                'notificationAction' => $notificationAction,
                'tokenListAndroid' => $tokenListAndroid,
                'tokenListIos' => $tokenListIos,
                'pushNotification' => $pushNotification
                ];

            $system_log = new SystemLog;
            $system_log->module = 'sendNotification';
            $system_log->logs = json_encode($allParams);
            $system_log->save();
        }
        
        return $pushNotification;
    } // EOF


    /**
     *
     */
    public function executeOrderNotification($language, $orderId, $orderFlag , $executeOrderNotificationScript, $shopsNearby,
    $shop) {

        // Firebase handling. Check the 'order_flag'. If order_flag:1 it is buyer-order, else seller-order
        $pushNotification = [];
        $app_service_location = env('APP_SERVICE_LOCATION');
        if ($app_service_location == 'china') {
            $jPushNoti = new JpushNotification;
        } else {
            $firebase = new FirebaseController;
        }
        
        // Notifications
        $eventScript = new EventScript;
        $eventScriptContent = new EventScriptContent;
        //===== Get eventScript
        $eventScriptId = $eventScript->getEventScriptIdByKeyName($executeOrderNotificationScript);
        $scriptContent = $eventScriptContent->getEventScriptContentByEventScriptIdAndLanguage($eventScriptId,
                                $language);
        $title = $scriptContent->title;
        $body = $scriptContent->body;
        $callFrom = Auth::user()->id;

        // Prepare token list
        $tokenListAndroid = [];
        $tokenListIos = [];
        $toUser = \Config::get('constants.notification.to_user.seller');
        $notificationType = \Config::get('constants.notification.notification_type.show');
        $notificationAction = \Config::get('constants.notification.notificatioin_action.execute_order');
        $appIsRing = env('NOTI_IS_RING');

        // Save notification
        if ($orderFlag == 1) {
            // For buyer
            if ($app_service_location != 'china' && $appIsRing == true) {
                $firebase->saveFirebase($orderId, Auth::user()->full_name);
                $orderFirebase = $firebase->executeOrderDetail($orderId);
                $notificationType = \Config::get('constants.notification.notification_type.hide');
            }

            // For seller
            $jsonReplaceStr = null;
            if (strpos($title, '{{ORDER_ID}}') != false) {
                $title = str_replace("{{ORDER_ID}}", $orderId, $title);
                $jsonReplace = ['ORDER_ID' => $orderId];
                $jsonReplaceStr = json_encode($jsonReplace);
            }
            // Build seller list to send notification
            $sellerIdList = [];
            $sellerList = [];
            foreach ($shopsNearby as $shopNearby) {
                foreach ($shopNearby->users as $u) {
                    if (!in_array($u->id, $sellerIdList)) {
                        $sellerIdList[] = $u->id;
                        $sellerList[] = $u;
                    }
                }
            }
            // Save & build token list
            foreach ($sellerIdList as $k => $i) {
                $notification = new Notification;
                $notification->saveNotification($sellerList[$k]->id, $scriptContent->id, $jsonReplaceStr, $notificationAction, $orderId, $orderFlag, 0, $toUser);
                
                if ($sellerList[$k]->device_type == "ios") {
                    $tokenListIos[] = $sellerList[$k]->fcm_token;
                } else if ($sellerList[$k]->device_type = "android" || $sellerList[$k]->device_type = "web") {
                    $tokenListAndroid[] = $sellerList[$k]->fcm_token;
                }
            }
        } else if ($orderFlag == 2) {
            // Then get supplier of this shop
            $supplier = new Shop;
            $supplier = Shop::where('id', $shop->supplier_id)->first();
            $userSupplier = User::where('id', $supplier->user_id)->first();

            //======= Save notification history
            $notification->saveNotification($userSupplier->id, $scriptContent->id, null, $notificationAction,
            $orderId, 0, $toUser);
            if ($userSupplier->device_type == 'ios') {
                $tokenListIos[] = $userSupplier->fcm_token;
            } else if ($userSupplier->device_type == 'android' || $userSupplier->device_type == "web") {
                $tokenListAndroid[] = $userSupplier->fcm_token;
            }
        }

        // Push notification
        if ($app_service_location == 'china') {
            $isRing = true;
            $acceptBy = '';
            if (count($tokenListAndroid) > 0) {
                $pushNotification["android"] = $jPushNoti->jPushNoti($orderId, $tokenListAndroid, $orderFlag, 
                    $body, $title, false, $callFrom, $acceptBy,
                    $toUser, $notificationType, $notificationAction);
            }
            if (count($tokenListIos) > 0) {
                $pushNotification["ios"] = $jPushNoti->jPushNoti($orderId, $tokenListAndroid, $orderFlag, 
                    $body, $title, false, $callFrom, $acceptBy,
                    $toUser, $notificationType, $notificationAction);
            }
        } else {
            if (count($tokenListAndroid) > 0) {
                $pushNotification["android"] = $firebase->pushNotificatoin($orderId, $tokenListAndroid, "android", 
                    $orderFlag, $body, $title,
                    $toUser, $notificationType, $notificationAction);
            }
            if (count($tokenListIos) > 0) {
                $pushNotification["ios"] = $firebase->pushNotificatoin($orderId, $tokenListIos, "ios", $orderFlag, $body, $title,
                $toUser, $notificationType, $notificationAction);
            }
        }

        // Debug
        // DEBUG ONLY
        if (env('SYS_LOG') == true) {
            $allParams = [
                'app_service_location' => $app_service_location,
                'orderId' => $orderId,
                'orderFlag' => $orderFlag,
                'callFrom' => $callFrom,
                'toUser' => $toUser,
                'title' => $title,
                'body' => $body,
                'notificationType' => $notificationType,
                'notificationAction' => $notificationAction,
                'tokenListAndroid' => $tokenListAndroid,
                'tokenListIos' => $tokenListIos,
                'pushNotification' => $pushNotification
                ];

            $system_log = new SystemLog;
            $system_log->module = 'executeOrderNotification';
            $system_log->logs = serialize($allParams);
            $system_log->save();
        }

        return $pushNotification;

    }

    /**
     *
     */
    public function sendNotificationToSpecificUser($language, $orderId, $orderFlag, $shopId, $eventScriptKey, 
        $user, $toUser, $notificationAction) {
        
        $notificationType = \Config::get('constants.notification.notification_type.show');

        // Notifications
        $eventScript = new EventScript;
        $eventScriptContent = new EventScriptContent;
        //===== Get eventScript
        $eventScriptId = $eventScript->getEventScriptIdByKeyName($eventScriptKey);
        $scriptContent = $eventScriptContent->getEventScriptContentByEventScriptIdAndLanguage($eventScriptId,
                                $language);
        $title = $scriptContent->title;
        $body = $scriptContent->body;

        $jsonReplaceStr = null;
        if (strpos($title, '{{ORDER_ID}}') != false) {
            $title = str_replace("{{ORDER_ID}}", $orderId, $title);

            $notification = new Notification;
            $jsonReplace = [
                'ORDER_ID' => $orderId
            ];
            $jsonReplaceStr = json_encode($jsonReplace);
        }
        
        /* BTY: 16112021, need to think of save notification for internal notification
        $notification = new Notification;
        $notification->saveNotification($user->id, $scriptContent->id, $jsonReplaceStr, $notificationAction,
        $orderId, $orderFlag, $shopId, $toUser);
        */

        $deviceType = $user->device_type;
        $fcmToken = $user->fcm_token;
        $fcmTokenList[] = $fcmToken;

        //======== Push notification
        $app_service_location = env('APP_SERVICE_LOCATION');
        if ($app_service_location == 'china') {
            $jPushNoti = new JpushNotification;
            $isRing = false;
            $callFrom = "";
            $acceptBy = auth()->user()->name;

            $pushNotification =  $jPushNoti->jPushNoti($orderId, $fcmTokenList, $orderFlag, $body, $title, false, $callFrom, $acceptBy,
            $toUser, $notificationType, $notificationAction);
            
        } else {
            $firebase = new FirebaseController;

            //====== Send notification to buyer
            $pushNotification = $firebase->pushNotificatoin($orderId , $fcmTokenList, $deviceType , 2, $body, $title,
            $toUser, $notificationType, $notificationAction);
        }
        return $pushNotification;
    }

    /**
     *
     */
    public function pushNotificationToAllUser($title, $body, $notificationAction) {
        // Prepare token list
        $firebase = new FirebaseController;
        $jPushNoti = new JpushNotification;
        $tokenListAndroid = [];
        $tokenListIos = [];
        $toUser = \Config::get('constants.notification.to_user.buyer');
        $notificationType = \Config::get('constants.notification.notification_type.show');
        $orderId = 0;
        $orderFlag = 0;
        $notificationInfo = new NotificationInfo;

        $users = User::where('is_active', 1)
            ->where('shop_id', 0)
            ->get();

        foreach($users as $user) {
            $notificationInfo->saveNotificationInfo($user->id, $title, $body, 
            $toUser, $notificationAction);
            if ($user->device_type == "ios") {
                $tokenListIos[] = $user->fcm_token;
            } else if ($user->device_type = "android" || $user->device_type = "web") {
                $tokenListAndroid[] = $user->fcm_token;
            }
        }

        $app_service_location = env('APP_SERVICE_LOCATION');
        if ($app_service_location == 'china') {
            $isRing = true;
            $callFrom = "";
            $acceptBy = "";
            if (count($tokenListAndroid) > 0) {
                $pushNotification["android"] = $jPushNoti->jPushNoti($orderId, $tokenListAndroid, $orderFlag, $body, $title, false, $callFrom, $acceptBy,
                $toUser, $notificationType, $notificationAction);
            }
            if (count($tokenListIos) > 0) {
                $pushNotification["ios"] = $jPushNoti->jPushNoti($orderId, $tokenListAndroid, $orderFlag, $body, $title, false, $callFrom, $acceptBy,
                $toUser, $notificationType, $notificationAction);
            }
        } else {
            if (count($tokenListAndroid) > 0) {
                $pushNotification["android"] = $firebase->pushNotificatoin($orderId, $tokenListAndroid, "android", $orderFlag, $body, $title,
                $toUser, $notificationType, $notificationAction);
            }
            if (count($tokenListIos) > 0) {
                $pushNotification["ios"] = $firebase->pushNotificatoin($orderId, $tokenListIos, "ios", $orderFlag, $body, $title,
                $toUser, $notificationType, $notificationAction);
            }
        }

        return $pushNotification;
    }
}