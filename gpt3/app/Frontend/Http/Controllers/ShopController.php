<?php

namespace App\Frontend\Http\Controllers;

use App\Frontend\Models\CityProvinceTranslate;
use App\Frontend\Models\District;
use App\Frontend\Models\DistrictTranslation;
use App\Frontend\Models\MembershipTranslation;
use App\Frontend\Models\ShopCategory;
use App\Frontend\Models\Shop;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Laracasts\Flash\Flash;

use function GuzzleHttp\Promise\all;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = null;
        $memberships = null;
        $cities = null;
        $districts = null;
        $shop_categories = null;
        $message = null;
        $locale = \App::getLocale();

        try {
            $memberships = MembershipTranslation::where('locale', $locale)->pluck('name', 'membership_id')->toArray();
            $shop_categories = ShopCategory::where('locale', $locale)->pluck('name', 'shop_category_id')->toArray();
            $cities = CityProvinceTranslate::where('locale', $locale)->pluck('name', 'city_province_id')->toArray();
            
            $myShop = Shop::where('user_id', auth()->user()->id)->first();

            if ($myShop) {
                $base_url = env('API_URL');
                $token = session()->get('token');

                $headers = [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'        => 'application/json',
                ];

                $client = new Client();
                $response = $client->request('GET', $base_url . '/shops/' . $myShop->id, [
                    'headers'  => $headers,
                    'verify' => false
                ]);
                $response = json_decode($response->getBody(), true);
                $shop = $response['data']['shop'];
                $districts = District::getDistrictByCity($shop['city_province_id'], Lang::getLocale())->toArray();
                // message when shop is under review
                if ($shop['is_active'] == 0) {
                    $message = __('frontend.msg_submission_under_review');
                }
            }
        } catch (QueryException $queryException) {
            Flash::error('Opp Somthing Error');
        } catch (Exception $e) {
            // return $e->getMessage();
            Flash::error('Opp Somthing Error');
        }

        return view('frontend::my.account.shops.index')->with([
            'memberships' => $memberships,
            'shop_categories' => $shop_categories,
            'cities' => $cities,
            'shop' => $shop,
            'districts' => $districts,
            'message' => $message
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric',
            'membership_id' => 'required',
            'city_province_id' => 'required',
            'district_id' => 'required',
            'supplier_id' => 'required',
            'address' => 'required',
            'logo_image'  =>  'mimes:jpeg,jpg,png,gif|required|max:10000',
            'cover_image'  =>  'mimes:jpeg,jpg,png,gif|required|max:10000',
        ]);

        try {
            $base_url = env('API_URL');
            $payloads = [];

            foreach ($request->all() as $key => $value) {
                $payloads[$key] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }

            $payloads['logo_image']['filename'] = $request->logo_image->getClientOriginalName();
            $payloads['logo_image']['contents'] = file_get_contents($request->logo_image);
            $payloads['cover_image']['contents'] =  file_get_contents($request->cover_image);
            $payloads['cover_image']['filename'] = $request->cover_image->getClientOriginalName();

            $base_url = env('API_URL');
            $token = session()->get('token');

            $client = new Client(["base_uri" => $base_url . '/open-shop']);
            $response = $client->request("POST", "", [
                'headers'  => [
                    // 'Content-Type' => 'multipart/form-data',
                    'authorization' => 'Bearer ' . $token
                ],
                'multipart' => $payloads,
                // 'json' => $payloads,
                'verify' => false,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return back()->with('success', $data['msg']);
        } catch (BadResponseException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true);
            return back()->withErrors(['error' => 'Opp something errors!'])->withInput();
        } catch (Exception $e) {
            // return $e->getMessage();
            return \Redirect::back()->withErrors(['error', $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     */
    public function getDistrictOfCity($id, Request $request)
    {
        $locale = \App::getLocale();
        $districts = \DB::table('districts')
            ->join('district_translations', 'districts.id', 'district_translations.district_id')
            ->where('districts.city_province_id', $id)
            ->where('district_translations.locale', $locale)
            ->pluck('district_translations.name', 'districts.id');

        if ($request->ajax()) {
            return response()->json(['status' =>  True, 'data' => $districts], 200);
        }
    }

    /**
     *
     */
    public function getSupplier(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'membership' => 'required',
            'city' => 'required',
            'district' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator], 422);
        }

        $shop = Shop::where(function ($query) use ($request) {
            if ($request->membership < 3) {
                $query->where('city_province_id', $request->city);
            }
            //$query->where('district_id', $request->district);
            $query->where('membership_id', $request->membership + 1);
        })
            ->pluck('name', 'id');

        return response()->json(['status' => true, 'data' => $shop], count($shop) > 0 ? 200 : 404);
    }

    /**
     *
     */
    public function visitShop()
    {
        $products = [];
        $count = 0;
        $products_session = session()->get('products');
        $shop_id = 1;
        $owner = '';

        if (count($products_session) > 0) {
            foreach ($products_session as $value) {
                $count++;
                // $shop_id = $value['shop_id'];
                $products[] = $value;
                if ($count >= 8) break;
            }
        }

        $shop = Shop::find($shop_id);
        $owner = $shop->user->full_name ?? '';
        return view('frontend::shops.visit_shop', compact('products', 'shop', 'owner'));
    }
}
