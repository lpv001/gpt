<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use Validator;

class PageController extends Controller
{
  /**
   *
   */
  public function getPage($url)
  {
    try {
      $page = Page::where('url', $url)->first();
      $status = true;
      $rcode = 200;
      $msg = 'get page successfully';
      if (!$page) {
        $status = false;
        $rcode = 404;
        $msg = 'get page not found';
      }
      
      $response = [
                  'status' => $status,
                  'msg' => $msg,
                  'data' => ['page' => $page]
                  ];
      return response()->json($response, $rcode);
    } catch (Exception $e) {

            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
    }
  } //EOF
}
