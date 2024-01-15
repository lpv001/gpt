<?php

namespace App\Frontend\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function curlTest(){
        $public_home_url = 'https://api.ganzberg.com/api/public/home?id=093216&token=GanGosFutureToken2020';
        $headers = [
            'Content-Type: application/json'
        ];

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $public_home_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            
            curl_close($ch);
            $data = json_decode($result, true);
            return dd($data);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
