<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;

class Phone extends Model
{
    protected $table = 'phones';
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'otp_code', 'otp_verified_id', 'signature', 'status'
    ];
    
    
    /**
     * 
     */
    private static function generateRandomString($length)
    {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }
    
    /**
     * 
     */
    public static function getOtpCode($args)
    {
      $number = env('COM_COUNTRY_CODE') . ltrim($args['number'], '0');
      $brand = $args['brand'];
      
      try {
        /*
        $verification = Nexmo::verify()->start([
            'number' => $number,
            'brand'  => $brand
        ]);
        $otp_verification_id = $verification->getRequestId();
        */
        
        // Generate 4 digits to send SMS
        $otp_code = mt_rand(1000,9999);
        
        // Generate 11 characters signature
        //$signature = 'PNqzG9C3LIi'; //Phone::generateRandomString(11);
        
        // Send SMS to user and get msg_id
        $txt = __('registration.sms_phone_verification', ['brand' => $brand, 'otp_code' => $otp_code]) . ' ' . $args['signature'];
        
        // Call provider
        $base_url = 'https://api.plasgate.com/send?token=NoG9sv5M4HN9w-zWsO4bF5Qf48C56L&phone=' . $number . '&senderID=SMS%20Info&text=' . rawurlencode($txt);
        $headers = ['Content-Type' => 'application/json'];
        $client = new Client();
        $response = $client->request('GET', $base_url, ['headers'  => $headers, 'verify' => false]);
        
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
          return ['status' => false, 'data' => []];
        }
        
        $res = json_decode($response->getBody()->getContents(), true);
        
        // Save database
        $data = [
          'phone' => $number, 
          'otp_code' => $otp_code, 
          'otp_verified_id' => Phone::generateRandomString(28), //$res[$number],
          'signature' => $args['signature']
        ];
        
        $phone = Phone::create($data);
        
        return ['status' => true, 'data' => $data];
      } catch (\Exception $e) {
          return [
              'status' => false,
              'msg' => $e->getMessage(),
              'data' => [],
          ];
      }
    }
    
    /**
     * 
     */
    public static function resendOtpCode($args)
    {
      $number = env('COM_COUNTRY_CODE') . ltrim($args['number'], '0');
      $brand = $args['brand'];
      
      try {
        /*
        $verification = Nexmo::verify()->start([
            'number' => $number,
            'brand'  => $brand
        ]);
        $otp_verification_id = $verification->getRequestId();
        */
        
        // Generate 4 digits to send SMS
        $otp_code = mt_rand(1000,9999);
        
        // Generate 11 characters signature
        $signature = Phone::generateRandomString(11);
        
        // Send SMS to user and get msg_id
        $txt = __('registration.sms_phone_verification', ['brand' => $brand, 'otp_code' => $otp_code]) . ' ' . $signature;
        
        // Call provider
        $base_url = 'https://api.plasgate.com/send?token=NoG9sv5M4HN9w-zWsO4bF5Qf48C56L&phone=' . $number . '&senderID=SMS%20Info&text=' . rawurlencode($txt);
        $headers = ['Content-Type' => 'application/json'];
        $client = new Client();
        $response = $client->request('GET', $base_url, ['headers'  => $headers, 'verify' => false]);
        $res = json_decode($response->getBody(), true);
        
        // Save database
        $data = [
          'phone'=>$number, 
          'otp_code' => $otp_code, 
          'otp_verified_id' => $res[$number],
          'signature' => $signature
        ];
        
        $phone = Phone::create($data);
        
        return ['status' => true, 'data' => $data];
      } catch (\Exception $e) {
          return [
              'status' => false,
              'msg' => $e->getMessage(),
              'data' => [],
          ];
      }
    }
    
    /**
     * 
     */
    public static function verifyOtpCode($args)
    {
      try {
        /*
        if (env('APP_ENV') != 'local') {
            Nexmo::verify()->check(
              $request->verified_request_id,
              $request->verified_code
            );
        }
        */
        
        $phone = Phone::where([
          ['otp_code', $args['otp_code']],
          ['otp_verified_id', $args['otp_verified_id']],
        ])->first();
        
        if (!$phone) {
          return ['status' => false, 'data' => [], 'msg' => __('registration.verification_code_invalid')];
        }
        
        return ['status' => true, 'data' => $phone];
      } catch (\Exception $e) {
          return [
              'status' => false,
              'msg' => $e->getMessage(),
              'data' => [],
          ];
      }
    }
}
