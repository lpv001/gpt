<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PaymentMethod;
use App\PaymentProvider;
use Validator;
use File;

class PaymentMethodController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;

    /**
     * Add new payment method
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPaymentMethod(Request $request){
        $image_path = '/uploads/images/payments';
        
        $validator = Validator::make($request->all(), [
            'shop_id' => 'numeric|required',
            'provider_id' => 'numeric|required',
            'code' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'message' => [
                    'kh' => $message,
                    'en' => $message,
                    'ch' => $message
                ],
                'data' => [],
            ];
            return response()->json($response, 401);
        }

        // Check not to allow duplicate entry
        try {
            $payment_method = PaymentMethod::where('shop_id', $request->input('shop_id'))
                    ->where('provider_id', $request->input('provider_id'))
                    ->first();
                    
            if($payment_method != null)
            {   
                $response = [
                'status'  => false,
                'message' => [
                    'kh' => 'Data already exist',
                    'en' => 'Data already exist',
                    'ch' => 'Data already exist',
                ],
                    'data' => [
                        'users' => $payment_method
                    ]
                ];
                return response()->json($response, 409);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'message' => [
                    'kh' => $e->getMessage(),
                    'en' => $e->getMessage(),
                    'ch' => $e->getMessage(),
                ],
                'data' => [],
            ];
            return response()->json($response, 40);
        }

        try {
            $payment_method = new PaymentMethod();
            $payment_method->shop_id = $request->input('shop_id');
            $payment_method->provider_id = $request->input('provider_id');
            
            // QR Code
            if($request->hasFile('code')){
                $qr_code = 'qr_code' . time() . '.' . request()->code->getClientOriginalExtension();
                $path = $request->file('code')->move(public_path($image_path), $qr_code);
                $payment_method->code = $qr_code;
            }
            
            $payment_method->save();
            
            // Load all payment methods of this seller
            $payment_methods = $payment_method->getShopPaymentMethod($request->input('shop_id'), 1);
            
            $response = [
                'status' => true,
                'message' => [
                    'kh' => 'Payment method was saved successfully',
                    'en' => 'Payment method was saved successfully',
                    'ch' => 'Payment method was saved successfully',
                ],
                'data' => [
                    'payment_methods' => $payment_methods
                ]
            ];
            return response()->json($response, $this->successCreated);

        } catch (\Exception $e) {
            $response = [
                'status' => false,
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

    /**
     * Add new payment method provider
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPaymentProvider(Request $request){
        $image_path = '/uploads/images/payments';
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
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
            $payment_provider = new PaymentProvider();
            $payment_provider->name = $request->input('name');
            $payment_provider->description = $request->input('description');
            
            // QR Code
            if($request->hasFile('icon')){
                $icon = 'icon' . time() . '.' . request()->icon->getClientOriginalExtension();
                $path = $request->file('icon')->move(public_path($image_path), $icon);
                $payment_provider->icon = $icon;
            }
            
            $payment_provider->save();

            $response = [
                'status' => true,
                'message' => [
                    'kh' => 'Payment method was saved successfully',
                    'en' => 'Payment method was saved successfully',
                    'ch' => 'Payment method was saved successfully',
                ],
                'data' => [
                    'payment_provider' => $payment_provider
                ]
            ];
            return response()->json($response, $this->successCreated);

        } catch (\Exception $e) {
            $response = [
                'status' => false,
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

    /**
     * Get a list payment method providers
     */
    public function getProviderList(){
        try{
            $payment_provider = new PaymentProvider;
            $payment_provider = $payment_provider->list();
            
            $response = [
                'status' => true,
                'message' => [
                    'kh' => 'Get payment method providers successfully',
                    'en' => 'Get payment method providers successfully',
                    'ch' => 'Get payment method providers successfully',
                ],
                'data' => $payment_provider,
            ];
            return response()->json($response, $this->successStatus);

        } catch (\Exception $e) {
            $response = [
                'status' => false,
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
