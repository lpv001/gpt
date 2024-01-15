<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Nexmo\Laravel\Facade\Nexmo;
use Validator;

use App\User;
use App\Phone;

class RegisterController extends Controller
{
    public $successStatus = 200;

    /**
     * Register api
     * BTY:210921: This function to be deleted.
     */
    public function getOtpCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:9|max:11|unique:users,phone'
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
          $otp_verification_id = 'localotp';
          $otp_phone = Phone::getOtpCode([
              'number' => str_replace(' ', '', $request->phone),
              'brand'  => env('APP_NAME')
          ]);
          $otp_verification_id = $otp_phone['data']['otp_verified_id'];
          
          $response = [
              'status' => true,
              'msg' => __('registration.get_otp_code_success'),
              'data' => [
                  'verify_request_id' => $otp_verification_id
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
     * Register
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'phone' => 'required|required|min:9|max:11|unique:users,phone',
            'password' => 'required',
            'verified_request_id' => 'required',
            'verified_code' => 'required|min:4|max:4',
            'fcm_token' => 'required',
            'device_type' => 'required'
        ]);

        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());

            $response = [
                'status' => false,
                'msg' => $message
            ];
            return response()->json($response, 400);
        }

        try {
          $res = Phone::verifyOtpCode([
              'otp_verified_id' => $request->verified_request_id,
              'otp_code'  => $request->verified_code
          ]);
          
          // Check if error
          if ($res['status'] == false) {
            $response = ['status' => false, 'msg' => $res['msg']];
            return response()->json($response, 404);
          }
          
          $input = $request->all();
          $input['password'] = bcrypt($input['password']);
          $input['phone_verify'] = true;
          $user = User::create($input);
          $success['token'] =  $user->createToken('MyApp')->accessToken;
          $success['fullName'] =  $user->full_name;
          $userRegister = $user;

          $response = [
              'status' => true,
              'msg' => __('registration.register_success'),
              'data' => [
                  'token' => $success['token'],
                  'user' => $userRegister
              ],
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
     * resend verification code
     */
    public function resendVerifiedCode(Request $request)
    {
        return $this->getOtpCode($request);
    }
}
