<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Admin\Models\ShopCategoryTranslation;
use App\CityProvinces;
use App\Country;
use App\Districts;
use App\PaymentMethod;
use App\Shop;
use App\Point;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoadConfigController extends Controller
{
    public function loadConfig()
    {
        try {
            // Load shop user relationship
            if (auth()->user()->shop == null) {
                foreach (auth()->user()->shops as $s) {
                    auth()->user()->shop = $s;
                }
            }
            
            // For mall-operator, must be under shop#1
            if ((auth()->user()->hasRole('mall-operator')) && (auth()->user()->shop == null)) {
                auth()->user()->shop = Shop::where('id', 1)->first();
            }
            
            $user = auth()->user();
            $user->profile_image = env('PUB_URL') . '/uploads/images/users/' . $user->profile_image;

            $shop = null;
            $payment_methods = null;
            $payment_method1 = null;
            $payment_method2 = null;
            $supplier = null;
            $country = null;
            $city = null;
            $district = null;

            // Get the Reward Points for this user.
            $point = new Point;
            $points = $point->getTotalPoints($user->id, 1);
            if ($points != null) {
                // Load points used
                $points->total_used = 0;
                $points->total_balance = $points->total_points;
                $points1 = $point->getTotalPoints($user->id, 0);
                if ($points1 != null) {
                    $points->total_used = $points1->total_points;
                    $points->total_points = $points->total_balance + $points->total_used;
                }
                $points->exchange_rate = 0.10;
            }

            if ($user->shop != null) {
                $shop = new Shop;
                $payment_methods = new PaymentMethod;
                $countrys = new Country;
                $citys = new CityProvinces;
                $districts = new Districts;

                $shop = $user->shop;
                $payment_method1 = $payment_methods->getShopPaymentMethod($user->shop_id, 'gateway');
                $payment_method2 = $payment_methods->getShopPaymentMethod($user->shop_id, 'qrcode');

                $supplierId = $shop->supplier_id;
                if ($supplierId != 0) {
                    $supplier = Shop::where('id', $supplierId)->first();
                    // Get supplier location
                    $country = $countrys->getCountryName($shop->country_id);
                    $city = $citys->getCityName($shop->city_province_id);
                    $district = $districts->getDistrictName($shop->district_id);
                }
            }

            // Version info
            $version = (object) [
                'android' =>
                [
                    'currentVersion' => '2.0.6',
                    'updateStatus' => 1,
                    'message' => 'The latest version is available on appstore now.  Please update now.'
                ],
                'ios' =>
                [
                    'currentVersion' => '2.0.6',
                    'updateStatus' => 1,
                    'message' => 'The latest version is available on appstore now.  Please update now.'
                ]
            ];

            $app = (object) [
                'ring_limit' => 60,
                'version' => $version
            ];

            $response = [
                'status'  => true,
                'msg' => 'Load successfully',
                'data' => [
                    'app' => $app,
                    'version' => $version,
                    'user' => $user,
                    'points' => $points,
                    'shop' => $shop,
                    'payment_methods' => [
                        'gateway' => $payment_method1,
                        'qrcode'  => $payment_method2
                    ],
                    'supplier' => $supplier,
                    'country_name' => $country,
                    'city_name' => $city,
                    'district_name' => $district
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response);
        }
    }
    
    /**
     * Public load config
     * 
     */
    public function publicLoadConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|min:6|max:6',
            'token' => 'required'
        ]);

        if ($validator->fails() || $request->input('token') != 'GanGosFutureToken2020') {
            $message = implode("", $validator->messages()->all());

            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }

        // Version info
        $version = (object) [
            'android' =>
            [
                'currentVersion' => '2.0.6',
                'updateStatus' => 1,
                'message' => 'The latest version is available on appstore now.  Please update now.'
            ],
            'ios' =>
            [
                'currentVersion' => '2.0.6',
                'updateStatus' => 1,
                'message' => 'The latest version is available on appstore now.  Please update now.'
            ]
        ];

        $response = [
            'status'  => true,
            'msg' => 'Load successfully',
            'data' => [
                'version' => $version
            ]
        ];
        return response()->json($response, 200);
    } //EOF
}
