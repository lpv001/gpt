<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SettingController extends Controller
{
    /**
     * Display a listing of the Setting.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $setting_product_point = $this->getSettingProductPoint();

        return view('admin::settings.index', compact('setting_product_point'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created User in storage.
     *
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        //
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function rateExchangeRedeemPoint(Request $request)
    {
        try {
            Setting::updateOrCreate([
                'setting_module' => 'product',
                'setting_key' => 'point',
            ], [
                'setting_value' =>  $request->product_point,
                'autload'   =>  1
            ]);

            return response()->json(['status' => true, 'message' => 'Update Successfully!'], 201);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function getSettingProductPoint()
    {
        $product_point = Setting::where([
            'setting_module' => 'product',
            'setting_key' => 'point',
        ])->first();

        return $product_point ? json_decode($product_point->setting_value, true) : [];
    }
}
