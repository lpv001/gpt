<?php

namespace App\Http\Controllers\API;

use App\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App;
use App\Http\Controllers\Controller;
use App\Product;
use App\Category;
use App\Shop;
use App\Http\Resources\ProductCollectionResource;
use App\ProductImage;
use DB;
use Exception;

class SearchController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    private $url = '/uploads/images/products/thumbnail';


    /**
     * Get products by searching name of product
     */
    public function typeAhead(Request $request)
    {
        $name = $request->input('q');
        //dd(Search::search($request->get('q'))->get());
        try {
            $searchs = DB::table('product_translations')
                ->where('name', 'like', '%' . $name . '%')
                ->orWhere('description', 'like', '%' . $name . '%')
                ->select('name', 'description')->get();
                
                $response = [
                    'status'  => true,
                    'msg' => __('products.get_product_success'),
                    'data' => $searchs
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
     * Get products by searching name of product
     */
    public function searchAll(Request $request)
    {
        $page =  1;
        $limit = 10;
        $locale = App::getLocale();
        $name = $request->input('name');
        
        try {
            $products = DB::table('products as p')
                ->leftJoin('product_images as pi', 'p.id', '=', 'pi.product_id')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->where('p.name', 'like', '%' . $name . '%')
                ->orWhere('c.default_name', 'like', '%' . $name . '%')
                ->select(
                    'p.id',
                    'p.name',
                    'p.description',
                    'c.default_name as category_name',
                    'p.sale_price',
                    'p.unit_price',
                    'p.unit_id',
                    'p.unit_id as unit_name',
                    DB::raw('CONCAT("' . env('PUB_URL') . $this->url . '/medium_", pi.image_name) AS image_name'),
                    'p.shop_id',
                    'p.shop_id as shop'
                )
                ->where('p.is_active', 1)
                ->groupBy('p.id')
                ->skip(($page - 1) * $limit)->take($limit)->get();

            if (count($products) > 0) {
                // Working on collection
                $shop_ids = [];
                $unit_ids = [];
                foreach ($products as $k => $p) {
                    $shop_ids[$p->shop_id] = $p->shop_id;
                    $unit_ids[$p->unit_id] = $p->unit_id;
                }
                
                $shops = Shop::whereIn('id', $shop_ids)->get();
                $units = DB::table('unit_translations')
                        ->whereIn('unit_id', $unit_ids)
                        ->where('locale', $locale)
                        ->select('unit_id', 'name')->get();
                $shop_list = [];
                $unit_list = [];
                foreach ($shops as $sk => $s) {
                    $shop_list[$s->id] = $s;
                }
                foreach ($units as $u) {
                    $unit_list[$u->unit_id] = $u->name;
                }
                
                foreach ($products as $k => $p) {
                    $products[$k]->shop = $shop_list[$p->shop_id];
                    $products[$k]->unit_name = $unit_list[$p->unit_id];
                }
                
                $response = [
                    'status'  => true,
                    'msg' => __('products.get_product_success'),
                    'data' => [
                        'products' => $products
                    ]
                ];
            } else {
                $response = [
                    'status'  => true,
                    'msg' => __('products.get_product_fail'),
                    'data' => [
                        'products' => $products
                    ]
                ];
            }

            return response()->json($response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF

    /**
     * Get products by searching name of product
     */
    public function searchCategory(Request $request)
    {
        $name = $request->input('name');
        try {
            $category = new Category;
            $categories = $category->getNameofCategory($name);

            $response = [
                'status'  => true,
                'msg' => __('products.get_category_success'),
                'data' => [
                    'categories' => $categories,
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
    public function searchPublicProducts(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $locale = \App::getLocale();

        $validator = \Validator::make($request->all(), [
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
            return response()->json($response, 401);
        }

            $products = Product::orWhereHas('translate', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->name . '%')
                    ->where('locale', \App::getLocale());
            })
                ->orWhereHas('brands', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->name . '%');
                })
                ->orWhereHas('categories', function ($query) use ($request) {
                    $query->where('default_name', 'LIKE', '%' . $request->name . '%');
                })
                ->select('id', 'name')
                ->orderBy('id', 'DESC')

            ->skip(($page - 1) * $limit)->take($limit)->get();

        $data = [
            'limit' => $request->input('limit', 10),
            'page'  => $request->input('page', 1)
        ];

        return (new ProductCollectionResource($products))->request($data);
    }

    /**
     *
     */
    public function filter(Request $request)
    {
        try {
            $products = Product::orWhereHas('translate', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->name . '%')
                    ->where('locale', \App::getLocale());
            })
                ->orWhereHas('brands', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->name . '%');
                })
                ->orWhereHas('categories', function ($query) use ($request) {
                    $query->where('default_name', 'LIKE', '%' . $request->name . '%');
                })
                ->select('id', 'name')
                ->orderBy('id', 'DESC')
                ->get();

            return [
                "status" => true,
                "message" => "Get successfully",
                "data" => $products
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => "Opp Somthing error. ",
                "data" => []
            ];
        }
    }
}
