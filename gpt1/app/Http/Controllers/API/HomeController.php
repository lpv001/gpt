<?php

namespace App\Http\Controllers\API;

use App\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use App\Shop;
use App\Product;
use App\Category;
use App\Brand;
use App\Banner;
use App\Http\Resources\HomeCollectionResource;
use App\Option;
use Exception;

use App\Http\Resources\CategoriesResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\BannerResource;


class HomeController extends Controller
{
    public $successStatus = 200;


    /**
     * TODO
     * 
     */
    private function getData()
    {
        try {
            $product = new Product;
            $category = new Category;
            $brand = new Brand;
            $banner = new Banner;

            $random_products = array();
            $promoted_products = array();
            $new_products = array();
            $products = array();
            $data = array();

            // Get base product price list
            $params = array('category_id' => null, 'type_id' => 0, 'city_id' => 0);
            $products = $product->getProductList($params, 30, $page);

            $response = [
                'status'  => true,
                'msg' => __('home.home_msg_get_deliveris_ok'),
                'data' => [
                    'random_products' => $random_products,
                    'promoted_products' => $promoted_products,
                    'new_products' => $new_products,
                    'categories' => $category->getListofCategory(),
                    'brands' => $brand->getListofBrand(),
                    'banners' => $banner->getActiveBannerList()
                ]

            ];
            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return $response;
        }
    } //EOF
    
    /**
     * Public API to home
     * 
     */
    public function getPublicHome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|min:6|max:6',
            'token' => 'required'
        ]);
        
        if ($validator->fails() || $request->input('token') != 'GanGosFutureToken2020') {
            $message = implode("", $validator->messages()->all());
            
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 404);
        }
        
        $limit = $request->input('limit', 12);
        $data = [
            'limit' => $limit,
            'page'  => $request->input('page', 1)
        ];
        
        $promoted_product_limit = $limit;
        if ($request->input('promoted_product_limit') > 0) {
          $promoted_product_limit = $request->input('promoted_product_limit');
        }
        
        // get promoted products
        $products = Product::inRandomOrder()->with('shop')->take($promoted_product_limit)->where('is_promoted', 2)->get();
        
        return (new HomeCollectionResource($products))->request($data);
    } //EOF
    
    /**
     * Public API to home
     * 
     */
    public function getPublicHomeNew(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|min:6|max:6',
            'token' => 'required'
        ]);
        
        if ($validator->fails() || $request->input('token') != 'GanGosFutureToken2020') {
            $message = implode("", $validator->messages()->all());
            
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 404);
        }
        
        // response
        $limit = $request->input('limit', 12);
        $page = $request->input('page', 1);
        try {
          // Promoted Products
          $promoted_product_limit = $limit;
          if ($request->input('promoted_product_limit') > 0) {
            $promoted_product_limit = $request->input('promoted_product_limit');
          }
          $promoted_products = Product::inRandomOrder()->where('is_promoted', 2)
                               ->with('shop')->take($promoted_product_limit)->get();

          // New Products
          $new_product_limit = $limit;
          if ($request->input('new_product_limit') > 0) {
            $new_product_limit = $request->input('new_product_limit');
          }
          $new_products = Product::where('is_active', 1)->orderBy('id', 'DESC')
                          ->with('shop')->take($new_product_limit)->get();

          // Collect products that skip below
          $skipIDs = [];
          foreach ($promoted_products as $k => $p) {
            $skipIDs[] = $p->id;
          }
          foreach ($new_products as $k => $p) {
            $skipIDs[] = $p->id;
          }
          
          // Random Products
          $random_product_limit = $limit;
          if ($request->input('random_product_limit') > 0) {
            $random_product_limit = $request->input('random_product_limit');
          }
          $random_products = Product::inRandomOrder()->with('shop')->whereNotIn('id', $skipIDs)
                             ->take($random_product_limit)->get();
          
          // Categories
          $category = new Category;
          $categories = $category->getCategories(0);
          
          // Brands
          $brand = new Brand;
          $brands =  $brand->getListofBrand();
          
          // Banners
          $banners =  Banner::get();
          
          $response = [
              'status'  => true,
              'msg' => 'Get home products success',
              'data' => [
                  'new_products' => $new_products,
                  'random_products' => $random_products,
                  'promoted_products' => $random_products,
                  'categories' => CategoriesResource::collection($categories),
                  'brands' => BrandResource::collection($brands),
                  'banners' => BannerResource::collection($banners)
              ]
          ];
          
          return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return $response;
        }
        
        
        
        
        $promoted_products = Product::take(3)->where('is_promoted', 2)->get();
        
        dd($promoted_products);
        
        $data = [
            'limit' => $request->input('limit', 12),
            'page'  => $request->input('page', 1)
        ];

        return (new HomeCollectionResource($products))->request($data);
    } //EOF
}
