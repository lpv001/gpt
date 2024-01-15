<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ShopController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
use App\Order;
use App\OrderItem;
use App\OrderOption;
use App\Shop;
use App\SystemLog;
use App\User;
use App\Notification;
use App\NotificationInfo;
use App\Helper\NotificationHelper;


class NotificationController extends Controller
{

    /**
     * getNotificationList
     */
    public function getNotificationList(Request $request) {
        try {
            $userId = auth()->user()->id;
            $isRead = 0;
            $notifications = Notification::where([['user_id', $userId], ['is_read', $isRead]])->select('notifications.*')->get();
            
            $response = [
                'status'  => true,
                'msg' => __('orders.notification_load_ok'),
                'data' => ['notification' => $notifications]
            ];
            
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 500);
        }
    }

    /**
     *
     */
    public function pushNotificationToAllUser(Request $request) {
        $title = $request->title;
        $body  = $request->body;
        $notificationAction = \Config::get('constants.notification.notificatioin_action.promotion');

        $notificationHelper = new NotificationHelper;
        $pushNotification = $notificationHelper->pushNotificationToAllUser($title, $body, $notificationAction);

        $response = [
            'status' => true,
            'msg' => __('orders.order_success'),
            'data' => [
                'pushLogs' => $pushNotification
            ],
        ];
        return response()->json($response, 200);
    }
}
