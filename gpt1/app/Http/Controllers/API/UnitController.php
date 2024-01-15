<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UnitTranslation;

class UnitController extends Controller
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
            $units = UnitTranslation::where('locale', $locale)->selectRaw('unit_id as id, name')->get();

            $response = [
                'status' => true,
                'msg' => "Get Units successfully.",
                'message' => [
                    'kh' => "Get units successfully.",
                    'en' => "Get units successfully.",
                    'ch' => "Get units successfully."
                ],
                'data' => $units
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
