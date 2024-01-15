<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Promotion;
use App\PromotionImage;
use Respone;
use DB;
use Exception;
use Validator;
use Carbon\Carbon;

class PromotionController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;

    /**
     *
     */
    public function getPromotionList(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $params = null;

        try {
            $promotion = new Promotion;
            $promotions = $promotion->getPromotionList($params, $limit, $page);

            $response = [
                'status'  => true,
                'msg' => 'Get promotions successfully',
                'data' => [
                    'promotions' => $promotions,
                    'request' => ['limit' => $limit, 'page' => $page]
                ]
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    public function verifyCode(Request $request)
    {
    try {
            $request->validate([
                'code' => 'required'
            ]);
            
            $promotion = Promotion::where(['code' => $request->code, 'is_active' => 1])
            ->whereDate('end_date', '>=', Carbon::now('Asia/Phnom_Penh'))
            ->first();
            $staus = false;
            $messsage = 'Your Code is invalid';
            $status_code = 200;
            $data = [];

            if ($promotion) {
                $staus = true;
                $messsage = 'Your Code is valid';
                $data = [
                    //'value' =>  number_format($promotion->flag == 1 ? $promotion->value : 100 - (100 * ($request->total - $promotion->value) / $request->total), 2),
                    'value' =>  number_format($promotion->value, 2),
                    'name'  =>  $promotion->name,
                    'flag'  =>  $promotion->flag,
                    'symbol'    =>  '%',
                ];
            }

            $response = [
                'status' => $staus,
                'msg' => $messsage,
                'data' => $data,
            ];

            return response()->json($response, $status_code);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 500);
        }
    }
}
