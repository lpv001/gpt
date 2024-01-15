<?php

namespace App\Frontend\Http\Controllers;

use App\Admin\Models\Promotion;
use App\Admin\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;

class RedeemGiftController extends Controller
{

    public function index(Request $request)
    {
        //
        $point = 100;
        $balance = 0;
        $redeem_items = $this->redeemGift();
        // $exchange_point_rate = Setting::where(['setting_module' => 'product', 'setting_key' => 'point_exchange'])->first(['value']);
        // $exchange_point_dollar = Setting::where(['setting_module' => 'product', 'setting_key' => 'point_to_dollars'])->first(['value']);

        try {
            $client = new Client();
            $base_url = env('API_URL');
            $token = session()->get('token');
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'        => 'application/json',
            ];
            $response = $client->request('GET', $base_url . '/rewards/my-points', [
                'headers'  => $headers,
                'verify' => false
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            if (array_key_exists('points', $response['data'])) {
                $point = $response['data']['points']['total_points'];
                $balance = $response['data']['points']['total_balance'];
            }
        } catch (Exception $e) {
            return redirect('/');
            return $e->getMessage();
        }

        return view('frontend::my.reedem-gift.index', compact('redeem_items', 'point', 'balance'));
    }

    public function redeemGift()
    {
        $redeem_item = Promotion::where(['promotion_type_id' => 0, 'is_active' => 1])->get();

        return $redeem_item;
    }

    public function exchangePoint(Request $request)
    {
        try {
            $request->validate([
                'point' => 'required',
            ]);

            $product_point = Setting::where([
                'setting_module' => 'product',
                'setting_key' => 'point',
            ])->first();

            if (!$product_point)
                return response()->json(['status' => false, 'message' => "Not found!"], 404);

            $setting_point = json_decode($product_point->setting_value, true);
            $exchanged = ($request->point / $setting_point['points_rate']) * $setting_point['rate_us'];

            return response()->json(['status' => true, "data" => ['point' => $request->point, 'exchanged' => $exchanged]], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
