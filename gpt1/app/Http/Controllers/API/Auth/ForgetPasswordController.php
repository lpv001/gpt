<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Nexmo\Laravel\Facade\Nexmo;
use Validator;
use App\User;
use App\Phone;

class ForgetPasswordController extends Controller
{
    /**
     *
     */
    public function getOptForForgetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:9|max:11',
        ]);
        
        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'code' => 400,
                'data' => [],
            ];
            return response()->json($response, 400);
        }
        
        try {
            $user = User::where('phone', $request->phone)->first();
            if ($user == null) {
                $response = [
                    'status' => false,
                    'msg' => __('registration.phone_not_exist'),
                    'code' => 404001,
                    'data' => [],
                ];
                
                return response()->json($response, 404);
            } else {
                $verify_request_id = 'localotpid';
                $otp_phone = Phone::getOtpCode([
                    'number' => str_replace(' ', '', $request->phone),
                    'signature' => $request->signature ?? '',
                    'brand'  => env('APP_NAME')
                ]);
                $verify_request_id = $otp_phone['data']['otp_verified_id'];
                
                $response = [
                    'status' => true,
                    'msg' => __('registration.get_otp_code_success'),
                    'data' => [
                        'verify_request_id' => $verify_request_id
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
    }

    /**
     * BTY:210921: This function to be deleted.
     */
    public function verifyOptForForgetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'verified_request_id' => 'required',
            'verified_code' => 'required|min:4|max:4',
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
        
        try {
            $verified_otp_id = 'localotp';
            $res = Phone::verifyOtpCode([
                'otp_verified_id' => $request->verified_request_id,
                'otp_code'  => $request->verified_code
            ]);
            
            /*
            if (env('APP_ENV') != 'local') {
              Nexmo::verify()->check(
                  $request->verified_request_id,
                  $request->verified_code
              );
            }
            */
            
            if ($res['status'] == false) {
              return response()->json(['status'=>false, 'msg'=>$res['msg']], 404);
            }
            
            $response = [
                'status' => true,
                'msg' => __('registration.verification_code_success'),
                'data' => ['verified_otp_id' => $request->verified_request_id],
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => __('registration.verification_code_invalid'),
                'data' => [],
            ];

            return response()->json($response, 401);
        }
    }

    /**
     *
     */
    public function forgetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|required|min:9|max:10',
            'password' => 'required',
            'fcm_token' => 'required',
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

        try {
            $input = $request->all();
            $password = bcrypt($input['password']);
            User::where('phone', $input['phone'])->update([
                'fcm_token' => $request['fcm_token'],
                'password' => $password,
                'device_type' => $input['device_type']
            ]);

            $user = User::where('phone', $input['phone'])->first();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['fullName'] =  $user->full_name;

            //$userRegister = User::where('id', Auth::user()->id)->first();
            $userRegister = $user;

            $response = [
                'status' => true,
                'msg' => "Your password was changed successfully",
                'data' => [
                    'token' => $success['token'],
                    'user' => $userRegister
                ],
            ];
            return response()->json($response, 200);

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
    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|required|min:9|max:10',
            'password' => 'required',
            'fcm_token' => 'required',
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

        try {
            $input = $request->all();
            $password = bcrypt($input['password']);
            User::where('phone', $input['phone'])->update([
                'fcm_token' => $request['fcm_token'],
                'password' => $password,
                'device_type' => $input['device_type']
            ]);

            $user = User::where('phone', $input['phone'])->first();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['fullName'] =  $user->full_name;

            //$userRegister = User::where('id', Auth::user()->id)->first();
            $userRegister = $user;

            $response = [
                'status' => true,
                'msg' => "Your password was changed successfully",
                'data' => [
                    'token' => $success['token'],
                    'user' => $userRegister
                ],
            ];
            return response()->json($response, 200);

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
