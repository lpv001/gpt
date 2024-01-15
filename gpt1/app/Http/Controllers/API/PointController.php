<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Point;
use App\SystemLog;
use Validator;
use Carbon\Carbon;
use App\User;
use App\Notification;


class PointController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;

    /**
     * Save order request helper function
     * @Author: BTY
     */
    private function savePoint($request) {
        // Create initiative order with no shop
        $point = new Point();
        $total = 0;
        $point->user_id = auth()->user()->id;
        $point->title = $request['title'];
        $point->save();

        return array('order' => $order, 'orderItems' => $order_items, 'deliveryOption' => $delivery_option, 'status' => true);
    }

    /**
     * List rewarded points
     */
    public function getPointList(Request $request) {
        try {
            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);
            $user_id = Auth::user()->id;
            $status_id = 1;
            $flag = 1;
            $point = new Point;
            $points = $point->getPoints($user_id, $page, $limit);

            if(count($points) > 0) {
                $response = [
                    'status' => true,
                    'msg' => 'Get point list successfully',
                    'message' => [
                        'kh' => 'Get point list successfully',
                        'en' => 'Get point list successfully',
                        'ch' => 'Get point list successfully',
                    ],
                    'data' => [
                        'points' => $points
                    ]
                ];
                return response()->json($response, $this->successStatus);
            } else {
                $response = [
                    'status' => false,
                    'msg' => 'No points found.',
                    'message' => [
                        'kh' => 'No points found.',
                        'en' => 'No points found.',
                        'ch' => 'No points found.',
                    ],
                    'data' => ['points' => $points],
                ];
                return response()->json($response, 204);
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
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * List rewarded points
     */
    public function getMyPoints(Request $request) {
        try {
            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);
            $user_id = Auth::user()->id;
            $status_id = 1;
            $flag = 1;
            $point = new Point;
            $points = $point->getTotalPoints($user_id);
            if ($points != null) {
                $points->total_used = 0;
                $points->total_balance = $points->total_points;
                $points1 = $point->getTotalPoints($user_id, 0);
                if ($points1 != null) {
                    $points->total_used = $points1->total_points;
                    $points->total_balance = $points->total_points - $points->total_used;
                }
                $points->records = $point->getPoints($user_id, $page, $limit);
                $points->exchange_rate = 0.10;
                
                $response = [
                    'status' => true,
                    'msg' => 'Get point list successfully',
                    'message' => [
                        'kh' => 'Get point list successfully',
                        'en' => 'Get point list successfully',
                        'ch' => 'Get point list successfully',
                    ],
                    'data' => [
                        'points' => $points
                    ]
                ];
                return response()->json($response, $this->successStatus);
            } else {
                $response = [
                    'status' => true,
                    'msg' => 'Fail!, can\'t get points',
                    'message' => [
                        'kh' => 'Fail!, can\'t get points',
                        'en' => 'Fail!, can\'t get points',
                        'ch' => 'Fail!, can\'t get points',
                    ],
                    'data' => [],
                ];
                return response()->json($response, 200);
            }
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
