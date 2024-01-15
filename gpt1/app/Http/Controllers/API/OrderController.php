<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\API\FirebaseController;
use App\Http\Controllers\API\JpushNotification;

use App;
use Validator;
use Carbon\Carbon;

use App\Category;
use App\Http\Resources\CategoriesResource;
use App\User;
use App\Notification;
use App\Order;
use App\OrderItem;
use App\OrderOption;
use App\OptionValue;
use App\Shop;
use App\Product;
use App\Point;
use App\OrderLog;
use App\SystemLog;
use App\EventScript;
use App\EventScriptContent;
use App\Helper\NotificationHelper;

use App\Helper\FileUploadHelper;

use App\OrderDiscount;
use App\OrderItemOption;
use App\OrderItemVariant;
use App\Delivery;
use App\PaymentMethod;
use App\Payment;
use App\Promotion;
use App\Redeem;
use App\Role;

class OrderController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;
    private $executeOrderNotificationScript = '';
    private $acceptedOrderNotificationScript = '';
    private $updateOrderStatusNotificationScript = '';
    private $completeOrderNotification = '';

    public function __construct()
    {
        $this->executeOrderNotificationScript =
            \Config::get('constants.event_script.notification.execute_order_notification');
        $this->acceptedOrderNotificationScript =
            \Config::get('constants.event_script.notification.accept_order_notification');
        $this->updateOrderStatusNotificationScript =
            \Config::get('constants.event_script.notification.update_order_status_notification');
        $this->completeOrderNotification =
            \Config::get('constants.event_script.notification.complete_order_notification');
    }

    /**
     * Save order request helper function
     * @Author: BTY, Channa
     */
    private function savePayment($order, $request)
    {
        $paymentMethod = PaymentMethod::find($request['payment_method_id']);
        $payment = new Payment;
        $payment->payment_method_id = $request['payment_method_id'];
        $payment->order_id = $order->id;
        $payment->amount = $order->total;
        
        // Save file
        if ($paymentMethod->flag != 0) {
            /*
            $screen_shot = '';
            $path = public_path('uploads/payments/screenshots');
            $screen_shot = FileUploadHelper::uploadImage($request['screen_shot'], $path);
            */
            $screen_shot = null;
            
            $payment->payment_account_id = $request['payment_account_id'];
            $payment->account_name = $request['account_name'];
            $payment->account_number = $request['account_number'];
            $payment->phone_number = $request['account_number'];
            $payment->code = $request['code'];
            $payment->screenshot = $screen_shot;
        }
        $payment->memo = $paymentMethod->name;
        $payment->save();
        
        return array('status' => true, 'payment' => $payment);
    }

    /**
     * Save order request helper function
     * @Author: BTY, Channa
     */
    private function saveOrder($request)
    {
        // Create initiative order with no shop
        $order = new Order();
        $total = 0;
        $total_point = 0;
        $orderStatusId = 0;
        $orderFlag = $request['order_flag'];
        $order->user_id = $request['user_id'];
        $order->shop_id = $request['shop_id'];
        
        // System logs
        if (env('SYS_LOG') == true) {
            $system_log = new SystemLog;
            $system_log->module = 'saveOrder';
            $system_log->logs = serialize($request);
            $system_log->save();
        }
        
        
        $order->date_order_placed = Carbon::now();
        $order->date_order_paid = date('Y-m-d', strtotime($request['date_order_paid']));
        $order->order_status_id = $orderStatusId;
        $delivery_option_id = isset($request['delivery_option_id_' . $order->shop_id]) ? $request['delivery_option_id_' . $order->shop_id] : 0;
        $order->delivery_option_id = $delivery_option_id;
        $order->delivery_address_id = $request['delivery_address_id'];
        $order->note = $request['note'];
        $order->preferred_delivery_pickup_date = date('Y-m-d', strtotime($request['preferred_delivery_pickup_date']));
        $order->preferred_delivery_pickup_time = $request['preferred_delivery_pickup_time'];
        $order->payment_method_id = $request['payment_method_id'];
        $order->delivery_pickup_date = date('Y-m-d', strtotime($request['delivery_pickup_date']));
        $order->pickup_lon = $request['pickup_lon'];
        $order->pickup_lat = $request['pickup_lat'];
        $order->save();

        // Save each order item
        if (isset($request['orderItem'])) {
            $items = $request['orderItem'];
        }
        if (isset($request['order_items'])) {
            $items = $request['order_items'];
        }
        if (isset($request['items'])) {
            $items = $request['items'];
        }
        $order_items = array();
        foreach ($items as $key => $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['product_id'];
            $orderItem->name = $item['name'];
            // This has to control to avoid error for now.
            if (isset($item['unit_id'])) {
                $orderItem->unit_id = $item['unit_id'];
            }
            $orderItem->unit_price = $item['unit_price'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->discount = $item['discount'];

            $item_total = ($item['quantity'] * $item['unit_price']) - $item['discount'];
            $total += $item_total;

            // Reward points
            if (!isset($item['point_rate'])) { // Check for compatible
                $item['point_rate'] = 0;
            }
            $orderItem->point_rate = $item['point_rate'];
            $orderItem->save();
            $order_items[] = $orderItem;

            // order product option
            if (!isset($item['options'])) {
                $item['options'] = [];
            }
            if (count($item['options']) > 0) {
                foreach ($item['options'] as $option) {
                  // load option_id because frontend does not provide this
                  $option_value = OptionValue::findOrFail($option['option_value_id']);
                  OrderItemOption::create([
                      'order_item_id' => $orderItem->id,
                      'product_id'    => $orderItem->product_id,
                      'option_id'     => $option_value->option_id,
                      'option_value_id' => $option['option_value_id']
                  ]);
                }
            }
            
            // variant option
            if (!isset($item['variants'])) {
                $item['variants'] = [];
            }
            if (count($item['variants']) > 0) {
                foreach ($item['variants'] as $variant) {
                    OrderItemVariant::create([
                        'order_item_id' => $orderItem->id,
                        'product_id'    => $orderItem->product_id,
                        'variant_id' => $variant['variant_id'],
                        'option_value_id' => $variant['option_value_id'],
                        'variant_price' => $variant['variant_price']
                    ]);
                }
            }
        }
        
        if (isset($request['total_use_point'])) {
            $order->total_use_points = $request['total_use_point'];
        }
        if (isset($request['point_exchange_rate'])) {
            $order->point_exchange_rate = $request['point_exchange_rate'];
        }
        
        // check if order discount
        if (isset($request['discounts'])) {
            $this->orderDiscount($order->id, $total, $request['discounts']);
        }
        
        $order->total = $total;
        $order->save();
        
        // Save payment
        $payment = $this->savePayment($order, $request);
        
        // Save delivery option
        $delivery = Delivery::findOrFail($delivery_option_id);
        $orderOption = new orderOption();
        $orderOption->order_id = $order->id;
        $orderOption->option_id = $delivery_option_id;
        $orderOption->name = $delivery->name;
        $orderOption->unit_price = $delivery->cost;
        $orderOption->flag = 1;
        $orderOption->save();
        
        return array('order' => $order, 'status' => true);
    }

    /**
     * Save order request helper function
     * @Author: BTY
     */
    private function saveOrders($request)
    {
        $order_list = ['items' => array()];
        $order_items = [];
        if (isset($request['orderItem'])) {
            $order_items = $request['orderItem'];
        }
        if (isset($request['order_items'])) {
            $order_items = $request['order_items'];
        }
        if (isset($request['items'])) {
            $order_items = $request['items'];
        }
        $product = new Product;
        foreach ($order_items as $k => $v) {
            $shop_id = 0;
            if (isset($v['shop_id'])) {
                $shop_id = $v['shop_id'];
            } else {
                $shop_id = $product->getProductShopId($v['product_id']);
            }
            $order_list['items'][$shop_id][] = $v;
        }

        $user_id = auth()->user()->id;
        $orderFlag = $request['order_flag'];
        $orders = array();
        $order = null;
        foreach ($order_list['items'] as $shop_id => $items) {
            $request['user_id'] = $user_id;
            $request['shop_id'] = $shop_id;
            $request['items'] = $items;
            $order = $this->saveOrder($request);
            $orders[$shop_id] = $order;
        }
        
        return array('status' => true, 'orders' => $orders);
    } // EOF

    /**
     * Validating order request fields helper function
     * @Author: BTY
     */
    private function validateOrderFields($request)
    {
        $validator = Validator::make($request->all(), [
            'order_flag' => 'required|numeric',
            //'delivery_option_id' => 'required|numeric',
            'address_full_name' => 'required',
            'address_email' => 'required',
            'address_phone' => 'required',
            'address_street_address' => 'required',
            'address_city_province_id' => 'required|numeric',
            'address_district_id' => 'required|numeric',
            'phone_pickup' => 'required',
            'preferred_delivery_pickup_date' => 'required|date',
            'preferred_delivery_pickup_time' => 'required',
            'payment_method_id' => 'required|numeric',
            'delivery_pickup_date' => 'required|date',
            'pickup_lat' => 'required|numeric',
            'pickup_lon' => 'required|numeric',
        ]);

        // Prepare response
        $response = ['status' => true];
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => ['request' => $request->all()],
            ];
        }
        return $response;
    }

    /**
     * Buyer request order
     * (1). Save order as inititiate status
     * (2). Push notification to seller via Firebase
     * @author: Sambath, Channa, BTY
     */
    public function executeOrder(Request $request)
    {
        try {
            $locale = App::getLocale();

            // Validate order request fields
            $response = $this->validateOrderFields($request);
            if ($response['status'] == false) {
                return response()->json($response, 401);
            }
            
            // Save order
            $orders = array();
            $orders = $this->saveOrders($request->all());
            if ($orders['status'] == false) {
                return response()->json($orders, 401);
            }
            
            // Get seller list for notification
            $shop_ids = array_keys($orders['orders']);
            $shop_list = DB::table('shops as s')
                ->join('users as u', 'u.id', '=', 's.user_id')
                ->select('s.id', 's.name', 's.address', 's.lat', 's.lng', 'u.fcm_token', 'u.device_type', 's.user_id')
                ->where('s.is_active', '=', 1)
                ->whereIn('s.id', $shop_ids)
                ->get();
            
            // Send notifications
            $notification = new NotificationHelper;
            $params = [];
            $order = null;
            foreach ($shop_list as $shop) {
                $order = $orders['orders'][$shop->id]['order'];
                $shop = Shop::find($shop->id);
                $params = [
                    'orderFlag' => 1,
                    'orderId'   => $order->id,
                    'notificationScript' => 'execute_order_notification', // replace above the same
                    'shopsNearby' => [$shop->id => $shop]
                ];
                $noti = $notification->sendNotification($params, $locale, 1);
            }
            
            // Prepare response
            $response_data = [
                'order_id' => $order->id,
                'order' => $order
            ];
            
            $response = [
                'status'  => true,
                'msg' => __('orders.order_saved'),
                'data' => $response_data
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            // System logs
            if (env('SYS_LOG') == true) {
                $system_log = new SystemLog;
                $system_log->module = 'executeOrderFailure';
                $system_log->logs = serialize($request->all());
                $system_log->save();
            }
            
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => ['request' => $request],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * Accepts order helper
     */
    private function processAcceptingOrder($order_id, $shop_id, $accept_ring)
    {
        try {
            $locale = \App::getLocale();
            $order = Order::findOrFail($order_id);

            //Check if this order has not yet been cancelled.
            if ($order->order_status_id == 4) {
                $response = [
                    'status' => false,
                    'code' => 401,
                    'msg' => __('This order has been processed.'),
                    'data' => ['order_status' => $order->order_status_id],
                ];
                return $response;
            }

            // Accept order by updating status
            $order->order_status_id = 1;
            $order->shop_id = $shop_id;
            $order->update();

            // Get info for push notification to buyer
            $buyer = User::where('id', $order->user_id)->first();

            // Save points if available.
            $orderItem = new OrderItem;
            $orderItems = $orderItem->getOrderItemByOrderId($order_id);
            $shop = Shop::where('id', $order->shop_id)->first();

            $total_point = 0;
            foreach ($orderItems as $key => $item) {
                $item_total = ($item->quantity * $item->unit_price) - $item->discount;
                $total_point += ($item_total * $item->point_rate) / 100;
            }

            if ($total_point > 0) {
                $point = new Point();
                $point->user_id = $order->user_id;
                $point->shop_id = $order->shop_id;
                $point->order_id = $order->id;
                $point->status = 1;
                $point->flag = 2;
                $point->title = 'Points earned from order#' . $order->id;
                $point->total = $total_point;
                $point->save();
            }

            // Save the used points if pay method use points
            if ($order->total_use_points > 0) {
                $point1 = new Point();
                $point1->user_id = $order->user_id;
                $point1->shop_id = $order->shop_id;
                $point1->order_id = $order->id;
                $point1->status = 0;
                $point1->flag = 3;
                $point1->title = 'Points used from order#' . $order->id;
                $point1->total = $order->total_use_points;
                $point1->save();
            }

            // Notifications
            $title = __('orders.accept_order_notification_title', ['order_id' => $order_id]);
            $body = __('orders.accept_order_notification_body');
            $firebase = new FirebaseController;
            $fcmTokenList = [$buyer->fcm_token];
            $pushNotification = $firebase->pushNotificatoin(
                $order_id,
                $fcmTokenList,
                $buyer->device_type,
                1,
                $body,
                $title,
                'buyer',
                'show',
                'accept_order'
            );

            $response = [
                'status' => true,
                'code' => $this->successStatus,
                'msg' => __('orders.order_success'),
                'data' => [
                    'order' => $order,
                    'shop' => $shop,
                    'order_item' => $orderItems,
                    'pushLogs' => $pushNotification
                ],
            ];
            return $response;
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'code' => 401,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return $response;
        }
    } // EOF

    /**
     * Seller accepts order
     */
    public function acceptOrder(Request $request)
    {
        try {
            // DEBUG ONLY
            if (env('SYS_LOG') == true) {
                $logs = ['request' => $request->all()];
                $system_log = new SystemLog;
                $system_log->module = 'acceptOrder';
                $system_log->logs = serialize($logs);
                $system_log->save();
            }

            $orderId = $request->input('order_id');
            $shop_id = $request->input('shop_id');
            $response = $this->processAcceptingOrder($orderId, $shop_id, true);

            return response()->json($response, $response['code']);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } // EOF

    /**
     * This need to move to OrderController
     * Use by buyer (push notification to seller)
     */
    public function cancelOrder(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            $order = Order::where('id', $orderId)->first();

            //====== Check if order has been processing (0: initiate)
            //====== only initiate status that can be cancelled
            if ($order->order_status_id != 0) {
                $response = [
                    'status' => false,
                    'msg' => __('This order can\'t be cancelled.'),
                    'data' => null,
                ];
                return response()->json($response, 401);
            }

            Order::where("id", $orderId)->update(['order_status_id' => 6]);

            // Notifications
            $firebase = new FirebaseController;
            $firebase->updateFirebase($orderId, 'BOUTY Shop', false);

            // OrderItems
            $orderItem = new OrderItem;
            $order = Order::findOrFail($orderId);
            $orderItems = $orderItem->getOrderItemByOrderId($orderId);
            $response = [
                'status' => true,
                'msg' => __('orders.order_success'),
                'data' => [
                    'order' => $order,
                    'order_item' => $orderItems,
                ],
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } // EOF

    /**
     * Provide order list by a user
     * If status_id = null, list all orders of selected user.
     * else list all orders for that status.
     */
    public function getMyOrderList(Request $request, $status_id)
    {
        try {
            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);
            $flag = 1;
            $user_id = Auth::user()->id;
            $order = new Order;
            $orders = $order->getOrder($user_id, $status_id, $page, $limit, $flag);
            
            $category = new Category;
            $categories = $category->getCategories(0);
            
            // Prepare stats
            $stat = array();
            $status_list = $order->getStatusList();
            foreach ($status_list as $k => $v) {
                $stat[$v] = $order->getStat($user_id, $k, 1);
            }
            
            if (count($orders) > 0) {
                $response = [
                    'status' => true,
                    'msg' => __('orders.get_order_list_success'),
                    'data' => [
                        'orders' => $orders,
                        'categories' => CategoriesResource::collection($categories),
                        'stat' => $stat
                    ]
                ];
                return response()->json($response, $this->successStatus);
            } else {
                $response = [
                    'status' => false,
                    'msg' => __('orders.get_order_list_fail'),
                    'data' => [
                      'orders' => [],
                      'categories' => $categories,
                      'stat' => $stat
                      ],
                ];
                return response()->json($response, 200);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * Provide order list by a user
     * If status_id = null, list all orders of selected user.
     * else list all orders for that status.
     */
    public function getMyShopOrderList(Request $request, $status_id)
    {
        try {
            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);
            $flag = 2;
            $shop = null;
            $shop_ids = [];

            if (Auth::user()->shops != null) {
                foreach (Auth::user()->shops as $shop) {
                    $shop_ids[] = $shop->id;
                }
            }

            $order = new Order;
            if (Auth::user()->hasRole('mall-operator')) {
                $orders = $order->getAllOrders($status_id, $page, $limit);
            } else {
                $orders = $order->getOrders($shop_ids, $status_id, $page, $limit, $flag);
            }
            if (count($orders) > 0) {
                $response = [
                    'status' => true,
                    'msg' => __('orders.get_order_list_success'),
                    'data' => [
                        'shop' => $shop,
                        'orders' => $orders,
                        //'stat' => $stat
                    ]
                ];
                return response()->json($response, $this->successStatus);
            } else {
                $response = [
                    'status' => true,
                    'msg' => __('orders.get_order_list_fail'),
                    'data' => [],
                ];
                return response()->json($response, 200);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * Provide order list by a user
     * If status_id = null, list all orders of selected user.
     * else list all orders for that status.
     */
    public function getOrdersByShop(Request $request, $shop_id, $status_id = null)
    {
        try {
            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);
            $flag = 0;
            $order = new Order;
            $orderItems = new OrderItem;
            $orders = $order->getOrder($shop_id, $status_id, $page, $limit, $flag);
            $shop = Shop::where('id', $shop_id)->first();
            
            // Prepare stats
            $stat = array();
            $status_list = $order->getStatusList();
            foreach ($status_list as $k => $v) {
                $stat[$v] = $order->getStat($shop_id, $k, 0);
            }
            
            if (count($orders) > 0) {
                $response = [
                    'status' => true,
                    'msg' => __('orders.get_order_list_success'),
                    'data' => [
                        'shop' => $shop,
                        'orders' => $orders,
                        'stat' => $stat
                    ]
                ];
                return response()->json($response, $this->successStatus);
            } else {
                $response = [
                    'status' => true,
                    'msg' => __('orders.get_order_list_fail'),
                    'data' => [],
                ];
                return response()->json($response, 401);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * Provide order specific by a order_id
     */
    public function Show($order_id)
    {
        try {
            $order = Order::findOrFail($order_id);
            $shop = Shop::where('id', $order->shop_id)->first();
            
            $response = [
                'status' => true,
                'msg' => __('orders.get_order_list_success'),
                'data' => [
                    'shop' => $shop,
                    'orders' => $order
                ]
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * Updating order action's status
     * @Required request fields
     * - order_id
     * - user_id
     * - shop_id
     */
    public function updateOrderStatus(Request $request)
    {
        try {
            $locale = \App::getLocale();
            $resp_msg = '';
            
            $orderId = $request->input('order_id');
            $order_status = $request->input('order_status_id');
            $shop_id = $request->input('shop_id');
            $shop = Shop::where('id', $shop_id)->first();
            
            // Accept order
            if ($order_status == 1) {
              $resp_msg = __('orders.order_accepted');
              $response = $this->processAcceptingOrder($orderId, $shop_id, false);
              return response()->json($response, $response['code']);
            } else {
              $resp_msg = __('orders.order_updated');
              Order::where('id', $orderId)->update(['order_status_id' => $order_status]);
            }
            
            $orderItem = new OrderItem;
            $order = Order::findOrFail($orderId);
            $orderItems = $orderItem->getOrderItemByOrderId($orderId);
            $user = "";
            $notificationAction = "";
            $toUser = "";
            $script = "";

            // Get buyer and seller for push notification
            $buyer = User::where('id', $order->user_id)->first();
            $seller = $shop;
            $pushNotification = null;
            
            if ($order_status == 3) {
                $notificationAction = \Config::get('constants.notification.notificatioin_action.received_order');
                $toUser = \Config::get('constants.notification.to_user.seller');
                $script = $this->completeOrderNotification;
                $notificationHelper = new NotificationHelper;
                $pushNotification = $notificationHelper->sendNotificationToSpecificUser($locale, $orderId, 0, 0, $script, $seller, $toUser, $notificationAction);
            } elseif ($order_status == 4) {
                $notificationAction = \Config::get('constants.notification.notificatioin_action.complete_order');
                $toUser = \Config::get('constants.notification.to_user.seller');
                $script = $this->completeOrderNotification;
                $notificationHelper = new NotificationHelper;
                $pushNotification = $notificationHelper->sendNotificationToSpecificUser($locale, $orderId, 0, 0, $script, $buyer, $toUser, $notificationAction);
            } else {
                $notificationAction = \Config::get('constants.notification.notificatioin_action.update_order_status');
                $toUser = \Config::get('constants.notification.to_user.buyer');
                $script = $this->updateOrderStatusNotificationScript;
                $notificationHelper = new NotificationHelper;
                $pushNotification = $notificationHelper->sendNotificationToSpecificUser($locale, $orderId, 0, 0, $script, $buyer, $toUser, $notificationAction);
            }

            $response = [
                'status' => true,
                'msg' => $resp_msg,
                'data' => [
                    'shop' => $shop,
                    'order' => $order,
                    'order_item' => $orderItems,
                    'pushLogs' => $pushNotification
                ],
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } // EOF

    /**
     * Get order by order id
     */
    public function getOrder($orderId)
    {
        try {
            $orderItem = new OrderItem;
            $orderOption = new OrderOption;
            $order = Order::findOrFail($orderId);
            $order->sub_total = $order->total;
            $shop = null;
            $discounts = [];
            $vat = 0;

            if ($order->shop_id != 0) {
                $shop = Shop::findOrFail($order->shop_id);
            }
            // old one
            $orderItems = $orderItem->getOrderItemByOrderId($orderId);
            
            // new
            /*
            $order_items =  OrderItem::where('order_id', $orderId)
                ->with([
                    'orderItemOption.options' => function ($query) {
                        return $query->select('id', 'name');
                    },
                    'orderItemOption.optionValue' => function ($query) {
                        return $query->select('id', 'name');
                    },
                    'variants' => function ($query) { // 
                        return $query->select('order_item_id', 'variant_id', 'option_value_id', 'variant_price');
                    },
                    'variants.optionValue' => function ($query) { // get variant option value
                        return $query->select('id', 'name');
                    },
                ])
                ->get();
            */
              
              // Deliveries
              $deliveryOption = $orderOption->getOrderOptionByOrderId($orderId);
              $delivery_address = DB::table('delivery_addresses')
                ->where('id', $order->delivery_address_id)
                ->first();
              $deliveries = ['methods' => $deliveryOption, 'addresses' => $delivery_address];
              
              // Discounts
              $order_discount = new OrderDiscount;
              $discounts = $order_discount->getDiscounts($orderId, $order->total);
              
              // Payments
              $payments = DB::table('payments as p')
              ->join('payment_methods as pm', 'p.payment_method_id', '=', 'pm.id')
              ->join('payment_accounts as pa', 'p.payment_account_id', '=', 'pa.id')
              ->join('payment_providers as pp', 'pa.provider_id', '=', 'pp.id')
              ->select(
                'pm.name as payment_method_name', 
                'pp.name as provider_name',
                'pa.account_name as payee_account_name', 
                'pa.account_number as payee_account_number',
                'pa.phone_number as payee_phone_number',
                'p.account_name as payer_account_name',
                'p.account_number as payer_account_number',
                'p.phone_number as payer_phone_number',
                'p.code as payment_code',
                'p.amount'
                )
              ->where('p.order_id', $order->id)
              ->first();
              // update order total
              $order->total = $order->sub_total - $discounts['total_discount'];
              
              $category = new Category;
              $categories = $category->getCategories(0);
              
              /*
              Order information includes
              - Order Info
              - orderItem
              - deliveryOption
              - discounts
              - options <-- not yet, should include
              
              In addition, should includes
              - user-buyer info
              - shop-seller info
              */
              $response = [
                  'status' => true,
                  'msg' => __('orders.get_order_success'),
                  'data' => [
                      'categories' => CategoriesResource::collection($categories),
                      'user' => User::findOrFail($order->user_id),
                      'shop' => $shop,
                      'order' => $order,
                      'order-item' => $orderItems,
                      'items' => $orderItems,
                      'deliveryOption' => $deliveryOption,
                      'discounts' => $discounts,
                      'deliveries' => $deliveries,
                      'payments' => $payments,
                      'vat' => $vat
                  ],
              ];
              return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    }

    /**
     * Get Buyer Order
     *
     */
    public function getBuyerOrderDelete()
    {
        try {
            $orderItem = new OrderItem;
            $orderItems = array();
            $orders = Order::with('order_items')->where('user_id', auth()->user()->id)->get();
            foreach ($item as $orders) {
                $orderItems[] = $orderItem->getShopByOrder($orders);
            }
            $response = [
                'status' => true,
                'msg' => __('orders.get_order_success'),
                'data' => [
                    'shop' => $order->getShopByOrder($orders->id),
                    'order' => $orders,
                    'order_items' => $orderItems
                ],
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    }


    /**
     * Get Order status list
     * Author: BTY
     */
    public function OrderStatus(Request $request)
    {
        try {
            $order_status = [
                ['id' => 1, 'name' => 'Completed'],
                ['id' => 2, 'name' => 'Delivered'],
                ['id' => 3, 'name' => 'Pending'],
                ['id' => 4, 'name' => 'Cancelled']
            ];

            $response = [
                'status' => true,
                'msg' => __('orders.get_order_status_success'),
                'data' => $order_status,
            ];
            return response()->json($response, $this->successCreated);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     *
     */
    public function orderDiscount($order_id, $total, $promotions_item)
    {
        $discounts = [];
        $total_discount = 0;
        
        // Membership discount
        $shop_id = 0;
        $seller = Shop::where(['user_id' => auth()->user()->id, 'is_active' => 1])->first();
        $price_settings = [1 => 'price_retailer', 2 => 'price_wholesaler', 3 => 'price_distributor'];

        // check if seller
        if ($seller) {
            $shop_id = $seller->id;
            if (array_key_exists($seller->membership_id, $price_settings)) {
                $seller_discount = Promotion::where('is_active', 1)->where('code', $price_settings[$seller->membership_id])->first();
                $discounts[] = [
                    'promotion_id'  =>  $seller_discount->id,
                    'code'          =>  $seller_discount->code,
                    'value'         =>  $seller_discount->value,
                    'flag'          =>  $seller_discount->flag
                ];
            }
        }

        // other discount
        if (count($promotions_item) > 0) {
            foreach ($promotions_item as $p) {
                $promotion = Promotion::where('is_active', 1)
                    ->where('code', $p['code'])
                    ->whereNotIn('promotion_type_id', [0, 1])->first();
                if ($promotion) {
                    $discounts[] = [
                        'promotion_id'  =>  $promotion->id,
                        'code'          =>  $promotion->code,
                        'value'         =>  $promotion->value,
                        'flag'          =>  $promotion->flag
                    ];
                    // update balance
                    $new_balance = $promotion->balance - 1;
                    $is_active = 1;
                    if ($new_balance <= 0) {
                        $is_active = 0;
                    }
                    $promotion->update([
                        'balance' => $new_balance,
                        'is_active' => $is_active
                    ]);
                }
            }
        }

        if (count($discounts) > 0) {
            foreach ($discounts as $item) {
                $total_discount += $item['flag'] == 1 ? $item['value'] : ($total * $item['value']) / 100;

                OrderDiscount::create([
                    'order_id'      =>  $order_id,
                    'discount_id'   =>  $item['promotion_id'],
                    'name'          =>  $item['code'],
                    'value'         =>  $item['value'],
                    'quantity'      =>  1,
                    'flag'          =>  $item['flag'],
                ]);
                
                /*
                // Save to redeem
                Redeem::create([
                    'user_id'      =>  auth()->user()->id,
                    'shop_id'      => $shop_id,
                    'promotion_id'   =>  $item['promotion_id'],
                    'value'         =>  $item['value'],
                    'qty'      =>  1,
                    'content'          =>  $item['code'],
                ]);
                */
            }
        }

        return ['order_id' => $order_id, 'total' => $total, 'discount' => $total_discount];
    }
}
