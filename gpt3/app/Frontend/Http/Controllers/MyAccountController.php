<?php

namespace App\Frontend\Http\Controllers;

use App\Admin\Models\Membership;
use App\Admin\Models\Order as ModelsOrder;
use Illuminate\Http\Request;
use App\Frontend\Models\Order;
use App\Admin\Models\OrderItem;
use App\Frontend\Models\CityProvince;
use App\Frontend\Models\District;
use App\Frontend\Models\ProductImage;
use Exception;
use GuzzleHttp\Client;

class MyAccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {
        return view('frontend::my.account.account');
    }


    public function profile_show()
    {
        return view('frontend::my.account.profile');
    }


    public function beameSeller()
    {
        $membership = Membership::pluck('name', 'id');
        $city = CityProvince::pluck('default_name', 'id');
        $district = District::pluck('default_name', 'id');


        return view('frontend::shops.became_saler', compact('membership', 'city', 'district'));
    }

    public function getDistrictOfCity($id)
    {
        try {
            $districts = District::where('city_province_id', $id)->pluck('default_name', 'id');
            return response()->json(['status' => true, 'data' => $districts]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e]);
        }
    }
}
