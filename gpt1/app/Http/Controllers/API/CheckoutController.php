<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use App\Product;
use App\Promotion;
use App\PaymentMethod;
use App\PaymentProvider;
use App\DeliveryAddress;
use App\DeliveryProvider;
use App\Delivery;
use App\Shop;

class CheckoutController extends Controller
{
    public $successStatus = 200;
    
    /**
     * return in km
     */
    private function calDistance($lat1, $lon1, $lat2, $lon2)
    {
      $pi80 = M_PI / 180;
      $lat1 *= $pi80;
      $lon1 *= $pi80;
      $lat2 *= $pi80;
      $lon2 *= $pi80;
      $r = 6372.797; // mean radius of Earth in km 
      $dlat = $lat2 - $lat1;
      $dlon = $lon2 - $lon1;
      $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      $km = $r * $c;
      //echo ' '.$km;
      return $km;
    }

    /**
     * Respone API to home
     */
    public function getCheckoutList(Request $request)
    {
        try {
            $product = new Product;
            $typeId = 0;
            // Get product for specific shop and location
            if (Auth::user()->shop != null) {
                if (Auth::user()->shop->status > 0) {
                    $typeId = Auth::user()->shop->membership_id;
                    $cityId = Auth::user()->shop->city_province_id;
                }
            }
            
            // Product settings
            $discounts = array();
            $price_settings = [1 => 'price_retailer', 2 => 'price_wholesaler', 3 => 'price_distributor'];
            if ($typeId > 0 && $typeId < 4) {
                $membership = \DB::table('membership_translations')->where([
                    'membership_id' => $typeId,
                    'locale'    =>  \App::getLocale()
                ])->first();

                $product_setting = Promotion::where(['promotion_type_id' => 1, 'code' => $price_settings[$typeId]])->first();
                $discounts[] = [
                    'name' => __('orders.membership_discount', [
                        'membership' => $membership ? $membership->name : '',
                        'discount' => $product_setting ? $product_setting->value : 0
                    ]),
                    'value' => (float) ($product_setting ? ($product_setting->value) : 0)
                ];
            }
            
            // Load all methods
            $payment_methods = PaymentMethod::with('accounts.provider')->get();
            
            // Build delivery options by each shop
            $my_addresses = DeliveryAddress::where(['user_id' => Auth::id(), 'is_active' => 1])->get();
            $delivery_address = null;
            if ($request->input('delivery_address_id') > 0) {
              $delivery_address = DeliveryAddress::where('id', $request->input('delivery_address_id'))->first();
            }
            
            $shops = Shop::whereIn('id', $request->input('shop_ids'))->get();
            $shop_list = [];
            foreach($shops as $shop) {
              $shop_city_id = 12;
              if ($shop->city_province_id > 0) {
                $shop_city_id = $shop->city_province_id;
              }
              $shop_lat = $shop->lat;
              $shop_lng = $shop->lng;
              if ($shop->lat < 1) {
                $shop_lat = 11.621615;
                $shop_lng = 104.906031;
              }
              
              $delivery = Delivery::where(['city_id1' => $shop->city_province_id, 'is_active' => 1]);
              if ($delivery_address) {
                $km = $this->calDistance($shop_lat, $shop_lng, $delivery_address->lat, $delivery_address->lng);
                $delivery->where('min_distance', '<=', $km);
                $delivery->where('max_distance', '>=', $km);
              }
              // Make sure there is deliveries found for this shop
              $shop_deliveries = $delivery->get();
              if (count($shop_deliveries) < 1) {
                $shop_deliveries = Delivery::where('is_active', 2)->get();
              }
              $deliveries[] = ['shop_id' => $shop->id, 'delivery_options' => $shop_deliveries];
              $shop_list[] = ['shop_id' => $shop->id, 'delivery_options' => $shop_deliveries];
            }
            
            $my_addresses = DeliveryAddress::where(['user_id' => Auth::id(), 'is_active' => 1])->get();
            $delivery_address = null;
            if ($request->input('delivery_address_id') > 0) {
              $delivery_address = DeliveryAddress::where('id', $request->input('delivery_address_id'))->first();
            }
            
            $response = [
                'status'  => true,
                'msg' => 'Get checkout list successfully',
                'data' => [
                    'discounts' => $discounts,
                    'my_addresses' => $my_addresses,
                    'delivery_address' => $delivery_address,
                    'shops' => $shop_list,
                    'payment_options' => $payment_methods,
                    'delivery_options' => $deliveries
                ]

            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return $response;
        }
    } //EOF
}
