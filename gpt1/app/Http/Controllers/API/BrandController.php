<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Brand;
use App\Product;
use App\ProductPrice;
use BrandTranslations;
use Validator;
use DB;

class BrandController extends Controller
{
    public $successStatus = 200;
    public $successCreated = 201;

    /**
     * Display list all of category
     * 
     */
    public function getBrandList()
    {
        try {
            $brands = new Brand();
            $brands = $brands->getListofBrand();
            $response = [
                'status'  => true,
                'msg' => 'Get brands successfully',
                'data' => [
                    'brands' => $brands
                ]
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * Display list all of brand
     * 
     */
    public function getBrands()
    {
        $brands = DB::table('brand_translations')->where('locale', \App::getLocale())->selectRaw('brand_id as id, name')->get();
        $response = [
            'status'  => true,
            'msg' => 'Get brands successfully',
            'data' => [
                'Brands' => $brands
            ]
        ];
        return response()->json($response, $this->successStatus);
    } //EOF

    /**
     * Display detail of brand
     * 
     */
    public function getDetailBrand($brand_id)
    {
        $brand = new Brand;
        $brand = $brand->getDetailofBrand($brand_id);
        $response = [
            'status'  => true,
            'message' => [
                'kh' => 'Get Brand detail successfully',
                'en' => 'Get Brand detail successfully',
                'ch' => 'Get Brand detail successfully',
            ],
            'data' => [
                'Brand' => $brand
            ]
        ];
        return response()->json($response, $this->$successStatus);
    } //EOF

    /**
     * Display list of product by category id
     * 
     */
    public function getProductBrand($brand_id)
    {
        try {
            $brand = new Brand;
            $products = $brand->getProductbyBrand($brand_id);

            $message = 'Get product successfully';
            $response = [
                'status'  => true,
                'msg' => $message,
                'message' => [
                    'kh' => $message,
                    'en' => $message,
                    'ch' => $message,
                ],
                'data' => [
                    'product' => $products
                ]
            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = [
                'status' => false,
                'message' => [
                    'kh' => $message,
                    'en' => $message,
                    'ch' => $message,
                ],
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF
}
