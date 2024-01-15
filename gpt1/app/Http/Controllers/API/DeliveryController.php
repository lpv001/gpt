<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Delivery;
use App\DeliveryProvider;
use Validator;

class DeliveryController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;

    /**
     * Get a list payment method providers
     */
    public function getDeliveryList(Request $request){
      try {
        $city_id1 = $request->input('city_id1');
        $city_id1 = $request->input('city_id1');
        
        $deliveries = Delivery::select(
              'id', 'name', 
              'city_id1', 'city_id2', 
              'min_distance', 'max_distance',
              'cost')
              ->where(['city_id1' => $city_id1, 'city_id2' => $city_id2])
              ->get();
        $message = __('orders.delivery_msg_get_deliveris_ok');
        $response = [
            'status' => true,
            'msg' => $message,
            'data' => $deliveries];
        return response()->json($response, $this->successStatus);
      } catch (\Exception $e) {
          $message = $e->getMessage();
          $response = [
              'status' => false,
              'msg' => $message,
              'data' => []];
          return response()->json($response, 401);
      }
    } //EOF

    /**
     * Add new delivery
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDelivery(Request $request) {
        $validator = Validator::make($request->all(), [
            'shop_id' => 'numeric|required',
            'provider_id' => 'numeric|required'
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

        // Check not to allow duplicate entry
        try {
            $delivery = Delivery::where('shop_id', $request->input('shop_id'))
                    ->where('provider_id', $request->input('provider_id'))
                    ->first();
                    
            if($delivery != null)
            {
                $message = __('orders.delivery_msg_data_exist');
                $response = [
                'status'  => false,
                'msg' => $message,
                    'data' => [
                        'users' => $payment_method
                    ]
                ];
                return response()->json($response, 409);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 40);
        }

        try{
            $delivery = new Delivery();
            $delivery->shop_id = $request->input('shop_id');
            $delivery->provider_id = $request->input('provider_id');
            $delivery->code = $request->input('code');
            $delivery->save();

            $message = __('orders.delivery_msg_save_delivery_ok');
            $response = [
                'status'  => true,
                'msg' => $message,
                'data' => [
                    'delivery' => $delivery
                ]
            ];
            return response()->json($response, 201);

        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => ['request' => $request],
            ];
            return response()->json($response, 401);
        }
    } //EOF
}
