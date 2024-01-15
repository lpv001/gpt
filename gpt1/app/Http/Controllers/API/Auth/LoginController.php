<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;
use App\Shop;
use App\Country;
use App\CityProvinces;
use App\Districts;
use App\PaymentMethod;
use App\UserRole;
use App\OrderStatus;
use Exception;

class LoginController extends Controller
{
    public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'device_type' => 'required'
        ], [
            'phone.required' => __('validation.msg_val_phone_require'),
            'password.required' => __('validation.msg_val_password_require'),
            'fcm_token.required' => __('validation.msg_val_fcm_token_require'),
            'device_type.required' => __('validation.msg_val_device_type_require')
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
        
        // Check if this phone exists
        $user = User::where('phone', $request['phone'])->first();
        if ($user == null) {
            $response = [
                'status' => false,
                'msg' => __('registration.phone_not_exist'),
                'code' => 404001,
                'data' => [],
            ];
            
            return response()->json($response, 404);
        }
        
        // authenticating
        $credentials = [
            'phone' => $request['phone'],
            'password' => $request['password']
        ];
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if this admin login
            $roles = [];
            if (isset($request['admin_login'])) {
              if ($request['admin_login']) {
                $roles = UserRole::where('user_id', $user->id)->whereIn('role_id', [1,2])->pluck('role_id')->toArray();
                if (count($roles) < 1) {
                  $response = [
                      'status' => false,
                      'msg' => 'No permission',
                      'data' => [],
                  ];
                  return response()->json($response, 401);
                }
              }
            }
            
            // Logout from other devices first. By BTY 27082020
            Auth::user()->AauthAcessToken()->delete();
            
            $user->profile_image = env('PUB_URL') . '/uploads/images/users/' . $user->profile_image;
            User::where('id', $user->id)->update([
                'fcm_token' => $request['fcm_token'],
                'device_type' => $request['device_type'],
                'is_active' => true
            ]);
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            
            $shop = null;
            $payment_methods = null;
            $payment_method1 = null;
            $payment_method2 = null;
            $supplier = null;
            $country = null;
            $city = null;
            $district = null;
            
            // This is the new shop user many relationship
            if (Auth::user()->shops != null) {
                foreach (Auth::user()->shops as $s) {
                    Auth::user()->shop = $s;
                    $shop = $s;
                }
            }
            
            if (Auth::user()->shop != null) {
                $shop = Auth::user()->shop;
                $payment_methods = new PaymentMethod;
                $countrys = new Country;
                $citys = new CityProvinces;
                $districts = new Districts;
                
                // $payment_method1 = $payment_methods->getShopPaymentMethod($shop->id, 'gateway');
                // $payment_method2 = $payment_methods->getShopPaymentMethod($shop->id, 'qrcode');
                $payment_method1 = [];
                $payment_method2 = [];
                
                $supplierId = $shop->supplier_id;
                if ($supplierId != 0) {
                    $supplier = Shop::where('id', $supplierId)->first();
                    // Get supplier location
                    $country = $countrys->getCountryName($shop->country_id);
                    $city = $citys->getCityName($shop->city_province_id);
                    $district = $districts->getDistrictName($shop->district_id);
                }
            }
            
            $order_status_list = [];
            $order_status = new OrderStatus;
            $order_status->code = 0;
            $order_status->name = 'order_status_initiate';
            $order_status_list[] = $order_status;
            
            $order_status = new OrderStatus;
            $order_status->code = 1;
            $order_status->name = 'order_status_accept';
            $order_status_list[] = $order_status;
            
            $order_status = new OrderStatus;
            $order_status->code = 2;
            $order_status->name = 'order_status_processing';
            $order_status_list[] = $order_status;
            
            $order_status = new OrderStatus;
            $order_status->code = 3;
            $order_status->name = 'order_status_delivered';
            $order_status_list[] = $order_status;
            
            $order_status = new OrderStatus;
            $order_status->code = 4;
            $order_status->name = 'order_status_received';
            $order_status_list[] = $order_status;
            
            $order_status = new OrderStatus;
            $order_status->code = 5;
            $order_status->name = 'order_status_completed';
            $order_status_list[] = $order_status;
            
            $order_status = new OrderStatus;
            $order_status->code = 6;
            $order_status->name = 'order_status_cancelled';
            $order_status_list[] = $order_status;
            
            $response = [
                'status' => true,
                'msg' => __('response.login_successfully'),
                'data' => [
                    'token' => $success['token'],
                    'user' => $user,
                    'roles' => $roles,
                    'shop' => $shop,
                    'payment_methods' => [
                        'gateway' => $payment_method1,
                        'qrcode'  => $payment_method2
                    ],
                    'supplier' => $supplier,
                    'country_name' => $country,
                    'city_name' => $city,
                    'district_name' => $district,
                    'order_status' => $order_status_list
                ],
            ];
            return response()->json($response, $this->successStatus);
        } else {
            $response = [
                'status' => false,
                'msg' => __('response.invalid_password_or_phone'),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    }

    /**
     * create for test respone
     */
    public function Testlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'device_type' => 'required'
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

        if (Auth::attempt(['phone' => request('phone'), 'password' => request('password')])) {
            $user = Auth::user();
            User::where('id', $user->id)->update(['fcm_token' => $request->fcm_token, 'device_type' => $request->device_type, 'is_active' => true]);
            $success['token'] =  $user->createToken('MyApp')->accessToken;

            $userLogin = User::where('id', Auth::user()->id)->first();
            $shopLogin = [];
            if ($user->membership_id != 5) {
                $shop = Shop::where('id', $user->shop_id)
                    ->first();
                // $country = Country::where('id', $shop->country_id)->first();
                return $shop;
            }

            $response = [
                'status' => true,
                'msg' => 'Login successfully',
                'data' => [
                    'token' => $success['token'],
                    'user' => $userLogin,
                    // 'shop' => $shopLogin
                ],
            ];
            return response()->json($response, $this->successStatus);
        } else {
            $response = [
                'status' => false,
                'msg' => "Phone number and password is not match.",
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    }

    public function storeFCMToken(Request $request)
    {
        $validated = $request->validate([
            'fcm_token' => 'required',
        ]);

        try {
            if (Auth::user()) {
                User::where('id', auth()->user()->id)->update(['fcm_token' => $request->fcm_token]);

                return response()->json([
                    'status'    =>  true,
                    'msg'   =>  'Update Successfully'
                ], 200);
            }

            return response()->json([
                'status'    =>  false,
                'message'   =>  'Update Fail'
            ], 500);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'msg' => "Phone number and password is not match.",
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    }
}
