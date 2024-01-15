<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Validator;
use App\Phone;

class PhoneController extends Controller
{
  
  /**
   * Get Phone OTP Code API
   *
   * @return \Illuminate\Http\Response
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
              'code' => 400,
              'data' => [],
          ];
          return response()->json($response, 400);
      }

      try {
        $otp_verification_id = 'localotp';
        $otp_phone = Phone::getOtpCode([
            'number' => str_replace(' ', '', $request->phone),
            'signature' => $request->signature ?? '',
            'brand'  => env('APP_NAME')
        ]);
        if ($otp_phone['status']) {
          $otp_verification_id = $otp_phone['data']['otp_verified_id'];
        }
        
        /*
        $verification = Nexmo::verify()->start([
            'number' => env('COM_COUNTRY_CODE') . str_replace(' ', '', $request->phone),
            'brand'  => env('APP_NAME')
        ]);
        $otp_verification_id = $verification->getRequestId();
        */
        
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
   *
   */
  public function verifyOptCode(Request $request) {
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
   * resend verification code
   * BOUTY: 161021, this to be deleted.
   */
  public function resendOtpCode(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'phone' => 'required|min:9|max:11|unique:users,phone'
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
        $otp_verification_id = 'localotp';
        $otp_phone = Phone::resendOtpCode([
            'number' => str_replace(' ', '', $request->phone),
            'brand'  => env('APP_NAME')
        ]);
        if ($otp_phone['status']) {
          $otp_verification_id = $otp_phone['data']['otp_verified_id'];
        }
        
        /*
        $verification = Nexmo::verify()->start([
            'number' => env('COM_COUNTRY_CODE') . str_replace(' ', '', $request->phone),
            'brand'  => env('APP_NAME')
        ]);
        $otp_verification_id = $verification->getRequestId();
        */
        
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
}
