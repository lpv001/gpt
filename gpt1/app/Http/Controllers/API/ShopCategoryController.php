<?php

namespace App\Http\Controllers\API;

use App\ShopCategory;
use App\ShopCategoryTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $shop_categories = ShopCategory::get();

            $response = [
                'status'  => true,
                'msg' => __('shops.get_shop_success'),
                'message' => [
                    'kh' => 'Get shops categories successfully',
                    'en' => 'Get shops categories successfully',
                    'ch' => 'Get shops categories successfully',
                ],
                'data' => $shop_categories
            ];

            return response()->json($response, 200);
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

    public function getList()
    {
        try {
            $shop_categories = ShopCategoryTranslation::where('locale', \App::getLocale())
                ->selectRaw('shop_category_id as id, name')->get();

            $response = [
                'status'  => true,
                'msg' => __('shops.get_shop_success'),
                'message' => [
                    'kh' => 'Get shops categories successfully',
                    'en' => 'Get shops categories successfully',
                    'ch' => 'Get shops categories successfully',
                ],
                'data' => $shop_categories
            ];

            return response()->json($response, 200);
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
