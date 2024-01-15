<?php

namespace App\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Cart;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Database\QueryException;
use GuzzleHttp\Exception\ClientException;
use function GuzzleHttp\Promise\all;
use Session;
use Flash;

use App\Http\Controllers\Controller;
use App\Frontend\Models\Product;
use App\Frontend\Models\Shop;
use App\Frontend\Models\Order;
use App\Frontend\Models\ShoppingCarts;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $carts = [];
        $discounts = [];
        $payment_options = [];
        $delivery_options = [];
        $my_address = [];
        $total = 0;
        $sub_total = 0;

        session()->forget('payment_options');
        session()->forget('payment_type');

        try {
            $base_uri = env('API_URL');

            // initial client get request
            $client = new Client(['base_uri' => $base_uri]);
            $access_token = session()->get('token');
            $response = $client->request(
                'GET',
                '/api/checkouts',
                [
                    'headers' => [
                        'Authorization' => "Bearer " . $access_token,
                        'Content-Language' => \App::getLocale(),
                    ]
                ]
            )->getBody()->getContents();
            $response = json_decode($response, true);

            // 
            $discounts = $response['data']['discounts'];
            $payment_options = $response['data']['payment_options'];
            session()->put('payment_options', $payment_options);

            $delivery_options = $response['data']['delivery_options']['delivery'];
            $my_address = $response['data']['my_addresses'];

            // get shopping cart items
            if ((session()->get('carts')) == null)
                session()->put('carts', $request->carts);

            $request->carts = $request->carts ? $request->carts : session()->get('carts');

            if (count($request->carts) > 0) {
                foreach ($request->carts as $key => $value) {
                    $carts[] = Cart::get($value);
                    $total += Cart::get($value)['price'] * Cart::get($value)['attributes']['qty'];
                }
            }

            // if there no cart then back to cart route
            if (count($carts) <= 0)
                return redirect()->back();
        } catch (BadResponseException $badResponse) {
            if ($badResponse->getCode() == 500) {
                Auth::logout();
                return redirect()->to('/login');
            }
            // return $badResponse->getResponse()->getBody()->getContents();
            Flash::error($badResponse->getResponse()->getBody()->getContents());
            // $request->session()->flash('error', $badResponse->getResponse()->getBody()->getContents());
        } catch (Exception $e) {
            // return $e->getMessage();
            Flash::error($e->getMessage());
            // $request->session()->flash('error', $e->getMessage());
        }

        $sub_total = $total;

        return view(
            'frontend::carts.checkout',
            compact(
                'carts',
                'discounts',
                'payment_options',
                'delivery_options',
                'total',
                'sub_total',
                'my_address'
            )
        );
    }

    /**
     *
     */
    public function index_completed()
    {
        return view('frontend::carts.checkout_completed');
    }

    /**
     *
     */
    public function excuteOrder(Request $request)
    {
        $request->validate([
            'payment_option_id' => 'required',
            'delivery_option_id' => 'required',
            'delivery_address_id' => 'required',
        ]);

        // 
        $user = auth()->user();
        $carts = [];

        try {
            if (count($request->carts) <= 0) {
                session::put('errors', ['message' => 'No shopping cart selected.', 'description' =>  '']);
                return redirect()->back();
            }

            foreach ($request->carts as $key => $item) {
                $cart = Cart::get($item);
                $product = [
                    "product_id"    => $cart->attributes->product_id,
                    "name"          => $cart->name,
                    "unit_id"       => $cart->attributes->unit_id,
                    "unit_price"    => $cart->price,
                    "quantity"      => $cart->attributes->qty,
                    "discount"      => 0,
                    "point_rate"    => $cart->attributes->point_rate,
                    "shop_id"       => $cart->attributes->shop_id,
                    'options'        =>  [],
                    'variants'      =>   $cart->attributes->variants
                ];
                $options_selected = [];
                $image_url = '';
                if (($cart->attributes->option) > 0) {
                    foreach ($cart->attributes->option as $key => $option) {
                        $options_selected[] = ['option_value_id' => $option['option_value_id']];
                        $image_url = $option['image'];
                    }
                }
                $product['image_url'] = $image_url;
                $product['options'] = $options_selected;
                $carts[] = $product;
            }

            // Prepare discounts
            $discounts = [['type' => 'coupon', 'code' => $request->coupon]];

            $payload = [
                "order_flag" => 1,
                "delivery_option" => $request->delivery_option_id ?? 0,
                "address_full_name" => "none",
                "delivery_address_id"   => $request->delivery_address_id,
                "address_email" => "none",
                "address_phone" => $request->phone ?? $user->phone,
                "address_street_address" => "none",
                "address_city_province_id" => 1,
                "address_district_id" => 1,
                "phone_pickup" => $user->phone,
                "preferred_delivery_pickup_date" => $request->date ?? Carbon::now(),
                "preferred_delivery_pickup_time" =>  $request->time ?? Carbon::today(),
                "payment_method_id" => $request->payment_option_id ?? 0,
                "delivery_pickup_date" => "2020/09/12",
                "pickup_lat" => !is_null($request->lat) ? $request->lat : 11.5564,
                "pickup_lon" => !is_null($request->lng) ? $request->lng : 104.9282,
                "delivery_option_id" => $request->delivery_option_id,
                "date_order_paid" => "2020/09/12",
                "note" => "noted",
                "total_use_point" => 0,
                "discounts"  =>  $discounts,
                "orderItem" => $carts,
                "order_total" => $request->order_total
            ];

            if ($request->payment_flag != 0) {
                session()->put('payload', $payload);
                session()->put('payment_type', $request->payment_option_id);
                return redirect()->to('checkout/payment');
            }

            $excute_order = $this->placeOrder($payload, null);
            if ($excute_order['status_code'] == 200) {
                Cart::clear();
                return view('frontend::carts.checkout_completed', ['order_id' => $excute_order['order_id']]);
            } else if ($excute_order['status_code'] == 500) {
                // This should show error message not to logout.
                Auth::logout();
                return redirect()->to('/login');
            } else {
                return back()->withErrors(['error' => $excute_order['message']])->withInput();
            }

            return view('frontend::carts.checkout_completed', compact('order_id'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     *
     */
    private function placeOrder($payload, $multipart)
    {
        $status = false;
        $statusCode = null;
        $message = null;
        $order_id = null;
        
        try {
            $base_url = env('API_URL');
            $token = session()->get('token');
            $client = new Client(["base_uri" => $base_url . '/orders/execute-order']);
            
            if ($multipart != null) {
                $flatten = function($array, $original_key = '') use (&$flatten) {
                    $output = [];
                    foreach ($array as $key => $value) {
                        $new_key = $original_key;
                        if (empty($original_key)) {
                            $new_key .= $key;
                        } else {
                            $new_key .= '[' . $key . ']';
                        }
                        if (is_array($value)) {
                            $output = array_merge($output, $flatten($value, $new_key));
                        } else {
                            $output[$new_key] = $value;
                        }
                    }
                    return $output;
                };

                $payload = $flatten($payload);

                $payloads = [];
                foreach($payload as $key => $value) {
                    $payloads[$key] = [
                        'name'  => $key,
                        'contents' => $value
                    ];
                }
                
                $file               = $multipart['screen_shot'];
                $file_path          = $file->getPathname();
                $file_mime          = $file->getMimeType('image');
                $file_uploaded_name = $file->getClientOriginalName();
                
                $payloads['screen_shot']['name'] = 'screen_shot';
                $payloads['screen_shot']['filename'] = $file_uploaded_name;
                $payloads['screen_shot']['Mime-Type'] = $file_mime;
                $payloads['screen_shot']['contents'] = fopen($file_path, 'r');
                
                $client = new Client(["base_uri" => $base_url . '/orders/execute-order']);
                $response = $client->request("POST", "", [
                    'headers'  => [
                        'authorization' => 'Bearer ' . $token
                    ],
                    'multipart' => $payloads,
                    'verify' => false,
                ]);
            } else {
                $response = $client->request("POST", "", [
                    'headers'  => [
                        'Content-Type' => 'application/json',
                        'authorization' => 'Bearer ' . $token
                    ],
                    'json' => $payload,
                    'verify' => false,
                ]);
            }
            
            // get status code
            $statusCode = $response->getStatusCode();
            // get response body
            $response = json_decode($response->getBody()->getContents(), true);
            $order_id = $response['data']['order_id'];
            $message = $response['msg'];
        } catch (BadResponseException $badResponse) {
            $statusCode = $badResponse->getCode();
            $message = json_decode($badResponse->getResponse()->getBody()->getContents(), true);
            $message = $message['msg'];
        } catch (Exception $e) {
            $message = $e->getMessage();
            $statusCode = 501;
        }
        
        return ['status' => true, 'status_code' => $statusCode, 'message' => $message, 'order_id' => $order_id];
    }

    /**
     *
     */
    private function removeCartItem($carts)
    {
        foreach ($carts as $key => $value) {
            Cart::remove($value);
        }
    }

    /**
     *
     */
    public function paymentType(Request $request)
    {
      $payload = session()->get('payload');
      $payment_options = session()->get('payment_options');
      $payment_type = session()->get('payment_type');
      
      if ($payment_options) {
          $payment_option = [];
          foreach ($payment_options as $key => $value) {
              if ($value['id'] == $payment_type) {
                  $payment_option = $value['accounts'];
              }
          }
          return view('frontend::carts.confirm-payment-type', compact('payment_option', 'payment_type', 'payload'));
      }
      
      return back();
    }

    /**
     *
     */
    public function excuteOrderWithoutCash(Request $request)
    {
        $payload = session()->get('payload');
        $payload['payment_account_id'] = $request->payment_account_id;
        $payload['account_name'] = $request->payment_account_name;
        $payload['account_number'] = $request->payment_account_number;
        $payload['account_phone'] = $request->payment_phone_number;
        $payload['code'] = $request->payment_code;
        $order_id = 0;
        $multipart = null;
        
        if ($request->hasFile('screen_shot')) {
            //$multipart = ['screen_shot' => $request->screen_shot[0]];
            $multipart = null; //disable screenshot for now.
        }
        
        try {
            $excute_order =  $this->placeOrder($payload, $multipart);
            if ($excute_order['status_code'] == 200) {
                Cart::clear();
                return view('frontend::carts.checkout_completed', ['order_id' => $excute_order['order_id']]);
            } else if ($excute_order['status_code'] == 500) {
                Auth::logout();
                return redirect()->to('/login');
            } else {
                return back()->withErrors(['error' => $excute_order['message']])->withInput();
            }
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     *
     */
    public function myLocation(Request $request)
    {
        return view('frontend::carts.checkouts.location');
    }

    /**
     *
     */
    public function saveDeliveryLocation(Request $request)
    {
        $request->validate([
            'tag' => 'required',
            'address' => 'required',
        ]);

        $base_url = env('API_URL');
        $token = session()->get('token');

        $payload = $request->all();
        try {
            $client = new Client(["base_uri" => $base_url . '/delivery-address']);
            $response = $client->request("POST", "", [
                'headers'  => [
                    'Content-Type' => 'application/json',
                    'authorization' => 'Bearer ' . $token
                ],
                'json' => $payload,
                'verify' => false,
            ]);

            return redirect()->route('checkout');
        } catch (BadResponseException $badResponse) {
            $response = json_decode($badResponse->getResponse()->getBody()->getContents(), true);
            return $request->session()->flash('errors', $response['msg']);
        }
    }
}
