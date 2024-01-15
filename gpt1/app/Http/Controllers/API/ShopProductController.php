<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Product;
use Exception;

class ShopProductController extends Controller
{
    //
    public function index(Request  $request)
    {

        try {
            $limit = $request->input('limit', 12);
            $page = $request->input('page', 1);

            $products = Product::where('user_id', auth()->user()->id)
                ->where('is_active', 1)
                ->orderBy('id', 'DESC')
                ->skip((intval($page) - 1) * intval($limit))
                ->take(intval($limit))
                ->get();

            $response = [
                'status' => true,
                'msg' => 'Get product successfully',
                'data' => ['products' => ProductCollection::collection($products)]
            ];

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'msg' => 'Ooop something error.',
                'data' => []
            ];

            return response()->json($response, 200);
        }

        // return $products;
    }



    public function delete($id)
    {
        if (!$id) {
            $response = [
                'status' => false,
                'msg' => 'id is required',
                'data' => [],
            ];
            return response()->json($response, 400);
        }

        $product = Product::find($id);

        if (!$product) {
            $response = [
                'status' => false,
                'msg' => 'Product not found',
                'data' => [],
            ];
            return response()->json($response, 400);
        }


        $product->update(['is_active' => 0]);
        $response = [
            'status' => true,
            'msg' => 'Product delete successfully',
            'data' => [],
        ];
        return response()->json($response, 200);
    }
}
