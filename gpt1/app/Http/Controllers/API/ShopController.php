<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App;
use App\SystemLog;
use App\Shop;
use App\User;
use App\Districts;
use App\PaymentMethod;
use Validator;
use File;
use App\Notification;
use App\Helper\NotificationHelper;
use App\Membership;
use App\Product;
use App\ProductPrice;
use App\Role;

class ShopController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;

    private $openShopNotificationScript = '';
    private $acceptedShopNotificationScript = '';
    private $rejectedShopNotificationScript = '';

    public function __construct()
    {
        $this->openShopNotificationScript =
            \Config::get('constants.event_script.notification.open_shop_notification');
        $this->acceptedShopNotificationScript =
            \Config::get('constants.event_script.notification.accept_shop_notification');
        $this->rejectedShopNotificationScript =
            \Config::get('constants.event_script.notification.reject_shop_notification');
    }

    /**
     * List all shops
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $shop = new Shop;
            $shops = null;
            $status = null;
            $limit = 10;
            $page = 1;
            $shop_id = null;
            if (Auth::user()->shop != null) {
                $shop_id = Auth::user()->shop->id;
            }

            if ($request->exists('limit')) {
                $limit = $request->input('limit');
            }
            if ($request->exists('page')) {
                $page = $request->input('page');
            }
            if ($request->exists('status')) {
                $status = $request->input('status');
            }

            // Get shops if this user is sellers.
            $stat_all = 0;
            $stat_pending = 0;
            $stat_accepted = 0;
            $stat_rejected = 0;
            if ($shop_id != null) {
                $shops = $shop->getShopApplied($shop_id, $status, $page, $limit);
                // show stats
                $stat_all = $shop->getStat($shop_id, 'all');
                $stat_pending = $shop->getStat($shop_id, 0);
                $stat_accepted = $shop->getStat($shop_id, 1);
                $stat_rejected = $shop->getStat($shop_id, 10);
            }
            $stat = (object)['all' => $stat_all, 'pending' => $stat_pending, 'accepted' => $stat_accepted, 'rejected' => $stat_rejected];

            $response = [
                'status'  => true,
                'msg' => __('shops.get_shop_success'),
                'data' => [
                    'shops' => $shops,
                    'stat' => $stat
                ]
            ];

            // System logs
            if (env('SYS_LOG') == true) {
                $system_log = new SystemLog;
                $system_log->module = 'shopIndex';
                $system_log->logs = $request->all();
                $system_log->save();
            }

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
     * Show a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'numeric']);
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }

        $shops = Shop::where('id', $id)
            ->first();

        if ($shops != null) {
            $response = [
                'status'  => true,
                'msg' => __('shops.get_shop_success'),
                'data' => [
                    'shop' => $shops
                ]
            ];
            return response()->json($response, $this->successStatus);
        } else {
            $response = [
                'status'  => true,
                'msg' => __('shops.get_shop_fail'),
                'data' => [
                    'shop' => $shops
                ]
            ];
            return response()->json($response, 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $locale = App::getLocale();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'logo_image' => 'mimes:jpeg,png,jpg,gif,svg',
            'cover_image' => 'mimes:jpeg,png,jpg,gif,svg',
            //'code' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'phone' => 'required',
            'country_id' => 'numeric|required',
            'city_province_id' => 'numeric|required',
            'district_id' => 'numeric|required',
            'address' => 'required|string',
            'lat' => 'numeric|required',
            'lng' => 'numeric|required',
            'membership_id' => 'numeric|required',
            //'supplier_id' => 'numeric|required',
            'shop_category_id'  =>  'numeric|required',
        ]);
        
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 400);
        }
        
        // Work around: need to check only 1 user has 1 shop, by BTY-20122019
        try {
            $chkshop = null;
            $chkshop = Shop::where('user_id', auth()->user()->id)->first();
            if ($chkshop != null) {
                $response = [
                    'status' => false,
                    'msg' => __('shops.data_already_exists'),
                    'data' => $chkshop,
                ];
                return response()->json($response, 409);
            }
        } catch (\Exception $e) {
            if (env('SYS_LOG') == true) {
                $logmsg = serialize($request->all()) . $e->getMessage();
                $system_log = new SystemLog;
                $system_log->module = 'openShopInternalError';
                $system_log->logs = $logmsg;
                $system_log->save();
            }
            $response = [
                'status' => false,
                'msg' => 'Open Shop Internal Error',
                'data' => [],
            ];
            return response()->json($response, 500);
        }
        
        try {
            $shop = new Shop();
            $shop->user_id = auth()->user()->id;
            $shop->name = $request->input('name');
            $shop->about = $request->input('about');
            //logo image
            if ($request->hasFile('logo_image')) {
                $image_name = 'logo' . uniqid() . time() . '.' . request()->logo_image->getClientOriginalExtension();
                $path = $request->file('logo_image')->move(public_path('/uploads/images/shops'), $image_name);
                $shop->logo_image = $image_name;
            }
            //cover image
            if ($request->hasFile('cover_image')) {
                $image_name = 'cover' . uniqid() . time() . '.' . request()->cover_image->getClientOriginalExtension();
                $path = $request->file('cover_image')->move(public_path('/uploads/images/shops'), $image_name);
                $shop->cover_image = $image_name;
            }
            
            //
            $shop->phone = $request->input('phone');
            $shop->country_id = $request->input('country_id');
            $shop->city_province_id = $request->input('city_province_id');
            $shop->district_id = $request->input('district_id');
            $shop->address = $request->input('address');
            $shop->lat = $request->input('lat');
            $shop->lng = $request->input('lng');
            $shop->membership_id = $request->input('membership_id');
            $shop->supplier_id = $request->input('supplier_id')?? 0;
            $shop->shop_category_id = $request->input('shop_category_id');
            $shop->status = 0;
            $shop->save();
            
            // Send notification to supplier
            // Prepare a user list to send notification
            $user_list = [];
            // If supply-chain, need to send to supplier, but market place no supplier
            $supplier_id = $request->input('supplier_id') ?? 1;
            if ($supplier_id > 0) {
              $supplierShop = Shop::findOrFail($supplier_id);
              $userId = $supplierShop->user_id;
              $user_list[$userId] = User::findOrFail($userId);
            }
            
            // Get additional mall operators for sending notifications
            $mall_operators = Role::where('slug','mall-operator')->get();
            if (count($mall_operators) > 0) {
              foreach ($mall_operators as $op) {
                foreach ($op->users as $u) {
                  $user_list[$u->id] = $u;
                }
              }
            }
            
            //======= Push Notification
            $notificationHelper = new NotificationHelper;
            foreach ($user_list as $u) {
              $pushNotification = $notificationHelper->sendNotificationToSpecificUser(
                  $locale,
                  0,
                  0,
                  $shop->id,
                  $this->openShopNotificationScript,
                  $u,
                  'seller',
                  'open_shop'
              );
            }
            
            $response = [
                'status' => true,
                'msg' => __('shops.save_shop_success'),
                'data' => [
                    'shop' => $shop
                ]
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
     * Update shop
     * 
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            //'logo_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            //'cover_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'phone' => 'required',
            'country_id' => 'numeric|required',
            'city_province_id' => 'numeric|required',
            'district_id' => 'numeric|required',
            'address' => 'required',
            'lat' => 'numeric|required',
            'lng' => 'numeric|required',
            'membership_id' => 'numeric|required',
            //'supplier_id' => 'numeric|required',
            'shop_category_id'  =>  'numeric|required',
        ]);
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 409);
        }
        
      try {
          $shop = Shop::findOrfail($id);
          
          $shop->name = $request->input('name');
          $shop->about = $request->input('about');
          
          // Old Images
          if (isset($request->logo_image_delete)) {
            $image_file = public_path('/uploads/images/shops') . '/' . $shop->logo_image;
            if (file_exists($image_file)) {
                unlink($image_file);
                $shop->logo_image = null;
            }
          }
          if (isset($request->cover_image_delete)) {
            $image_file = public_path('/uploads/images/shops') . '/' . $shop->cover_image;
            if (file_exists($image_file)) {
                unlink($image_file);
                $shop->cover_image = null;
            }
          }
          
          // New images
          if ($request->hasFile('logo_image')) {
              $image_name = 'logo' . uniqid() . time() . '.' . request()->logo_image->getClientOriginalExtension();
              $path = $request->file('logo_image')->move(public_path('/uploads/images/shops'), $image_name);
              $shop->logo_image = $image_name;
          }
          if ($request->hasFile('cover_image')) {
              $image_name = 'cover' . uniqid() . time() . '.' . request()->cover_image->getClientOriginalExtension();
              $path = $request->file('cover_image')->move(public_path('/uploads/images/shops'), $image_name);
              $shop->cover_image = $image_name;
          }
          
          $shop->phone = $request->input('phone');
          $shop->country_id = 1; //set Country to Cambodia
          $shop->city_province_id = $request->input('city_province_id');
          $shop->district_id = $request->input('district_id');
          $shop->address = $request->input('address');
          $shop->lat = $request->input('lat');
          $shop->lng = $request->input('lng');
          $shop->membership_id = $request->input('membership_id');
          $shop->supplier_id = $request->input('supplier_id')?? 0;
          $shop->shop_category_id = $request->input('shop_category_id');
          $shop->update();
          
          $response = [
              'status' => true,
              'msg' => __('shops.update_shop_success'),
              'data' => $shop,
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
     * Remove the specified Shop from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
      try {
        $shop = Shop::findOrfail($id);
        
        // Delete images
        $logo_image = $this->path . '/' . $shop->logo_image;
        if (file_exists($logo_image)) {
            unlink($logo_image);
        }
        $cover_image = $this->path . '/' . $shop->cover_image;
        if (file_exists($cover_image)) {
            unlink($cover_image);
        }
        $res=Shop::where('id',$id)->delete();
        
        $response = [
            'status' => true,
            'msg' => __('shops.update_shop_success'),
            'data' => [],
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
    public function MemberShipList()
    {
        $locale = \App::getLocale();

        $supplier = \DB::table('memberships')
            ->join('membership_translations', 'memberships.id', 'membership_translations.membership_id')
            ->where(function ($query) use ($locale) {
                $query->where('memberships.key', '!=', 'buyer');

                $query->where('membership_translations.locale', $locale);
            })
            ->select('memberships.id', 'membership_translations.name')
            ->get();

        $response = [
            'status' => true,
            'msg' => __('shops.get_supplier_success'),
            'data' => $supplier,
        ];
        return response()->json($response, $this->successStatus);
    }

    /**
     *  getMemberships
     */
    public function getMemberships($supplier_id, $status_id = null)
    {
        try {
            $shops = new Shop;
            $shops = $shops->getMemberships($supplier_id, $status_id);
            if (count($shops) > 0) {
                $response = [
                    'status' => true,
                    'msg' => __('shops.get_shop_success'),
                    'data' => $shops,
                ];
                return response()->json($response, $this->successStatus);
            } else {
                $response = [
                    'status' => true,
                    'msg' => __('shops.get_shop_fail'),
                    'data' => $shops,
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
    } // EOF

    /**
     *
     */
    public function getShops_supplier($city_id, $shop_level)
    {
        try {
          
          $query = Shop::where('city_province_id', $city_id);
          $query->where('city_province_id', $city_id);
          if ($shop_level > 0) {
            $query->where('membership_id', $shop_level);
          }
          $shop = $query->pluck('name', 'id');
          
          return response()->json(['status' => true, 'data' => $shop], count($shop) > 0 ? 200 : 404);
         
          
          
          
          
          
          
          
          
            $shops = new Shop;
            //check if its Distributor
            if ($shop_level == 3) {
                $shop = $shops->where("membership_id", $shop_level + 1)
                    ->select('id', 'name', 'membership_id')
                    ->get();
            } else if ($shop_level == 5) {
                $shop = $shops->where("membership_id", 4)
                    ->select('id', 'name', 'membership_id')
                    ->get();
            } else {
                $shop = $shops->where("district_id", $district_id)
                    ->where("membership_id", $shop_level + 1)
                    ->select('id', 'name', 'membership_id')
                    ->get();
            }

            if (count($shop) > 0) {
                $response = [
                    'status'  => true,
                    'msg' => __('shops.get_shop_success'),
                    'data' => [
                        'Suppliers' => $shop
                    ]
                ];
                return response()->json($response, $this->successStatus);
            } else {
                $response = [
                    'status'  => true,
                    'msg' => __('shops.get_supplier_fail'),
                    'data' => [
                        'Suppliers' => []
                    ]
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
     *
     */
    public function getApplier(Request $request)
    {
        try {
            $limit = 10;
            $shopId = Auth::user()->supplier_id;
            $shop = new Shop;
            $shops = $shop->getShopApplied($shopId, null, $request->page, $limit);
            $response = [
                'status' => true,
                'msg' => __('shops.get_shop_success'),
                'data' => $shops,

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
     * Use to approval memebership or other purpose when to update shop depending on the request params.
     * @Author: BTY, Channa
     * status: 1=approve, 10=reject
     */
    public function updateShop(Request $request)
    {
        $language = $request->header('Content-Language') == null ? "en" : $request->header('Content-Language');
        $locale = App::getLocale();
        
        // Validate status must be present
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'shop_id' => 'required'
        ]);
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());

            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }
        
        // Updating
        try {
            $shop_id = $request->input('shop_id');
            Shop::where("id", $shop_id)->update(['status' => $request->input('status')]);
            $shop =  Shop::where("id", $shop_id)->first();
            $response = null;
            
            $userId = $shop->user_id;
            $user = User::where('id', $userId)->first();
            
            //TODO push notification to user
            // Prepare token list
            $msg = "";
            $message = "";
            $toUser = \Config::get('constants.notification.to_user.buyer');
            $notificationAction = "";
            $scriptKey = '';
            
            if ($request->input('status') == 1) {
                Shop::where('id', $shop_id)->update(['is_active' => 1]);
                User::where("id", $userId)->update([
                    'shop_id' => $shop->id,
                    'membership_id' => $shop->membership_id,
                    'supplier_id' => $shop->supplier_id
                ]);
                
                $notificationAction = \Config::get('constants.notification.notificatioin_action.accept_shop');
                
                $msg = "shops.shop_approved";
                $message = "Shop approved successfully.";
                $scriptKey = $this->acceptedShopNotificationScript;
            } else {
                $notificationAction = \Config::get('constants.notification.notificatioin_action.reject_shop');
                
                $msg = "shops.shop_rejected";
                $message = "Shop have been rejected.";
                $scriptKey = $this->rejectedShopNotificationScript;
            }

            //======= Push Notification
            $notificationHelper = new NotificationHelper;
            $pushNotification = $notificationHelper->sendNotificationToSpecificUser(
                $locale,
                0,
                0,
                $shop_id,
                $scriptKey,
                $user,
                $toUser,
                $notificationAction
            );

            $response = [
                'status' => true,
                'msg' => __('shops.shop_rejected'),
                'data' => [
                    'shop' => $shop,
                    'pushLogs' => $pushNotification
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
     * Get nearby shops
     */
    public function getNearbyShops($lat, $lon)
    {
        $shop = new Shop;
        $nearbyShops = null;
        $distance = 20;
        $user_id = 0;

        // Check if public user
        if (isset(Auth::user()->id)) {
            $user_id = Auth::user()->id;
        }

        try {
            $nearbyShops = $shop->getShopsNearby($lat, $lon, $distance, $user_id);
            $response = [
                'status' => true,
                'msg' => "Get shops successfully.",
                'data' => ['shops' => $nearbyShops]
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
     *
     */
    public function getShopProductList(Request $request, $status = null)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        $shop_id = auth()->user()->shop->id;
        try {
            $products = Product::where('shop_id', $shop_id)
                ->with('images', 'prices', 'names')
                ->orderBy('id', 'DESC')
                ->skip(($page - 1) * $limit)->take($limit);
            if ($status != null && is_numeric($status)) {
                $products = $products->where('status', $status);
            }
            $products = $products->get();

            $total = Product::where('shop_id', $shop_id)->count();
            $response = [
                'status'  => true,
                'msg' => 'Get products successfully',
                'data' => [
                    'products' => $products,
                    'total'   =>   $total,
                    'request' => ['limit' => $limit, 'page' => intval($page)]
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
    }

    /**
     *
     */
    public function getShopProductDetail($id)
    {
        try {
            $product = Product::where([
                'id'        =>  $id,
                'shop_id'   =>  auth()->user()->shop_id
            ])
                ->with('images', 'prices')
                ->first();

            $response = [
                'status'  => true,
                'msg' => 'Get product successfully',
                'data' => [
                    'products' => $product,
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
    }

    /**
     *
     */
    public function getShopDetailByUser($user_id)
    {
        try {
            $shop = Shop::where('user_id', $user_id)->first();

            $response = [
                'status'  => true,
                'msg' => 'Get shop successfully',
                'data' => [
                    'shop' => $shop,
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
    }


}
