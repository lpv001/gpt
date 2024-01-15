<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DeliveryProvider;
use Validator;
use File;

class DeliveryProviderController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;

    /**
     * Add new delivery provider
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDeliveryProvider(Request $request){
        $image_path = '/uploads/images/deliveries';
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'cost' => 'required|numeric',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'message' => [
                    'kh' => $message,
                    'en' => $message,
                    'ch' => $message
                ],
                'data' => [],
            ];
            return response()->json($response, 401);
        }

        try {
            $delivery_provider = new DeliveryProvider();
            $delivery_provider->name = $request->input('name');
            $delivery_provider->cost = $request->input('cost');
            $delivery_provider->description = $request->input('description');
            
            // QR Code
            if($request->hasFile('icon')){
                $icon = 'icon' . time() . '.' . request()->icon->getClientOriginalExtension();
                $path = $request->file('icon')->move(public_path($image_path), $icon);
                $delivery_provider->icon = $icon;
            }
            
            $delivery_provider->save();

            $response = [
                'status' => true,
                'msg' => __('admin.delivery_msg_provider_was_saved'),
                'message' => [
                    'kh' => 'Delivery provider was saved successfully',
                    'en' => 'Delivery provider was saved successfully',
                    'ch' => 'Delivery provider was saved successfully',
                ],
                'data' => [
                    'delivery_provider' => $delivery_provider
                ]
            ];
            return response()->json($response, $this->successCreated);

        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'message' => [
                    'kh' => $e->getMessage(),
                    'en' => $e->getMessage(),
                    'ch' => $e->getMessage(),
                ],
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF
}
