<?php

namespace App\Helper;

class HttpResponse
{
    // successfully
    static $ok = 200;
    static $created = 201;


    // unauthorize
    static $unauthorize = 401;
    static $forbidden = 403;
    static $notfound = 403;

    // server errors
    static $serverError = 500;



    static function response($code = 200, $message, $data)
    {
        return response()->json([
            'status' => $code == 200 || $code == 201 ? true : false,
            'msg' =>    $message,
            'data'  =>  $data
        ], $code);
    }
}
