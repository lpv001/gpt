<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ProductSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $price_distributor = Setting::where(['setting_module' => 'product', 'setting_key' => 'price_distributor'])->first();
        $price_wholsaler = Setting::where(['setting_module' => 'product', 'setting_key' => 'price_wholesaler'])->first();
        $price_retailer = Setting::where(['setting_module' => 'product', 'setting_key' => 'price_retailer'])->first();

        return view('admin::setting.product_setting.index', compact('price_distributor', 'price_wholsaler', 'price_retailer'));
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
        try {
            if (isset($request->productPrice)) {
                $arr_key = ['price_distributor', 'price_wholesaler', 'price_retailer'];
                foreach ($request->productPrice as $key => $value) {
                    Setting::updateOrCreate(
                        [
                            'setting_module' => 'product',
                            'setting_key' => $arr_key[$key],
                        ],
                        [
                            'setting_value' =>  $value ?? 0
                        ]
                    );
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Save Successfully',
                ], 201);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   => 'Something error!'
            ], 500);
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
