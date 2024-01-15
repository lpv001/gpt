<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CityProvinces;
use App\Districts;
use App\Country;

class LocationsController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;

    /**
     *
     */
    public function LiveLocation(Request $request ,$country){
        
        $country = $request->segment(3);
        
        if($country == 'kh'){

        $provinces = CityProvinces::pluck("default_name","id");
      
        $response = [
            'status'  => true,
            'msg' => __('orders.location_get_ok'),
            'data' => [
                'Provinces' => $provinces
            ]
        ];
        return response()->json($response, $this->successStatus);
        }
        else{
            $response = [
                'status'  => true,
                'msg' => __('orders.location_get_ok'),
                'data' => [
                    'Provinces' => $provinces
                ]
            ];
        }
    }

    /**
     *
     */
    public function getCountry(){
        try {
            $country = Country::get();

            $response = [
                'status'  => true,
                'msg' => __('orders.location_get_ok'),
                'data' => [
                    'Country' => $country
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
    }

    /**
     *
     */
    public function getProvinceList($country_id) {
        try {
            $province = new CityProvinces;
            $provinces = $province->getProvinceList($country_id);
            
            if ($provinces->count() > 0) {
                $response = [
                    'status'  => true,
                    'msg' => __('orders.location_get_ok'),
                    'data' => [
                        'provinces' => $provinces
                    ]
                ];
                return response()->json($response, $this->successStatus);
            } else {
                $response = [
                    'status'  => true,
                    'msg' => __('orders.location_not_found'),
                    'data' => []
                ];
                return response()->json($response, 401);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($response, 401);
        }
    }

    /**
     *
     */
    public function getDistrictList($province_id){
        try {
            $district = new Districts;
            $districts = $district->getDistrictList($province_id);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
        
        if ($districts->count() > 0)
            {   
                $response = [
                    'status'  => true,
                    'msg' => __('orders.location_get_ok'),
                    'data' => [
                        'Districts' => $districts
                    ]
                ];
                return response()->json($response, $this->successStatus);
            } else{
                $response = [
                    'status'  => true,
                    'msg' => __('orders.location_not_found'),
                    'data' => [
                        'Districts' => $districts
                    ]
                ];
                return response()->json($response, 401);
            }
    }
}
