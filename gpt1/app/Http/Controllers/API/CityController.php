<?php

namespace App\Http\Controllers\API;

use App\CityTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $successStatus = 200;
    public $successCreated = 201;

    public function index()
    {
        //
        try {
            $locale = \App::getLocale();
            $cities = CityTranslation::where('locale', $locale)->selectRaw('city_province_id as id, name')->get();

            $response = [
                'status' => true,
                'msg' => "Get Cities successfully.",
                'message' => [
                    'kh' => "Get city successfully.",
                    'en' => "Get city successfully.",
                    'ch' => "Get city successfully."
                ],
                'data' => $cities
            ];
            return response()->json($response, $this->successStatus);
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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
