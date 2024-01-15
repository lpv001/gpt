<?php

namespace App\Frontend\Http\Controllers;

use App\Admin\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Frontend\Helpers\ClientService;
use App\Frontend\Models\Product;
use App\Frontend\Models\Category;
use App\Frontend\Models\ProductImage;
use App\Frontend\Models\Shop;
use Illuminate\Support\Facades\Auth;
use DB;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $products = [];
        $categories = [];
        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 12;
            $category = $request->ajax() ? \Request::get('cat') : $request->cat;

            $base_uri = env('API_URL') ? env('API_URL') : 'https://api.ganzberg.com/api';
            $url = 'public/search?id=093216&token=GanGosFutureToken2020&page=';
            $url .= $page . '&limit=' . $limit . '&name=' . $request->q .  '&min_price=' . $request->min_price . '&max_price=' . $request->max_price;

            $basicauth = new Client(['base_uri' => $base_uri]);
            $headers = [
                'Content-Type'        => 'application/json',
                'Content-Language'  =>  \App::getLocale()
            ];


            $newresponse = $basicauth->request('POST', '/api/' . $url, $headers)->getBody()->getContents();
            $data = json_decode($newresponse, true);
            $products = $data['data']['products'];


            if (isset($request->q)) {
                $categories_response = $basicauth->request('GET', '/api/categories')->getBody()->getContents();
                $categories = json_decode($categories_response, true);
                $categories = $categories['data'];
            }

            if ($request->ajax()) {
                $view = view('frontend::products.components.item')->with(['products' => $products, 'classColumn' => 'col-md-4 items mb-4'])->render();
                return response()->json(['status' => true, 'data' => $view, 'paramQuery' => $request->all()]);
            }
        } catch (BadResponseException $badResponse) {
            // dd($badResponse);
        } catch (ClientException $clientException) {
            // dd($clientException);
        } catch (RequestException $e) {
            // return $e->getPMessage();
        }

        return view('frontend::products.search', compact('products', 'categories', 'category'));
    }

    /**
     *
     */
    public function seachByCategory(Request $request, $id)
    {
        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 12;

            $url = 'categories/products/' . $id . '?';
            $url .= 'page=' . $page . '&limit=' . $limit;
            $url =  ClientService::getBaseUri() . '/' . $url;
            $newresponse = $this->client->request('GET', $url, [
                'headers'  => ClientService::getHeaders(),
                'verify' => false
            ]);
            $newresponse = $newresponse->getBody()->getContents();
            $data = json_decode($newresponse, true);
            
            //$products = $data['data']['products'];
            //$categories = $data['data']['categories'];

            $category_name = '';
            $products = [];
            $categories = [];
            $brands = [];
            $related_categories = [];
            if (count($data['data']) > 0) {
                $products = $data['data'][0]['products'];
                $related_categories = $data['data'][0]['sub_categories'];
                $categories = $data['data'][0]['categories'];
                $brands = $data['data'][0]['brands'];
            }
            if ($request->ajax) {
                return response()->json([
                    'products' => $products,
                    'request' => [
                        'page' => $page,
                        'limit' => $limit
                    ]
                ]);
            }
        } catch (BadResponseException $badResponse) {
            //dd($badResponse);
        } catch (ClientException $clientException) {
            //dd($clientException);
        } catch (Exception $e) {
            return $e->getMessage();
            return redirect()->route('/');
        }

        return view('frontend::products.search', compact('products', 'categories', 'related_categories', 'brands'));
    }

    /**
     *
     */
    public function seachByBrand(Request $request, $id)
    {
        $products = [];
        $brands = [];
        $categories = [];

        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 12;

            $url = 'brands/products/' . $id . '?';
            $url .= 'page=' . $page . '&limit=' . $limit;
            $url =  ClientService::getBaseUri() . '/' . $url;
            $newresponse = $this->client->request('GET', $url, [
                'headers'  => ClientService::getHeaders(),
                'verify' => false
            ]);
            $newresponse = $newresponse->getBody()->getContents();
            $data = json_decode($newresponse, true);

            $products = $data['data']['products'];
            $brands = $data['data']['brands'];
            $categories = $data['data']['categories'];

            if ($request->ajax) {
                return $data;
                return response()->json([
                    'products' => $products,
                    'request' => [
                        'page' => $page,
                        'limit' => $limit
                    ]
                ]);
            }

            if ($request->ajax()) {
                $view = view('frontend::products.components.item')->with(['products' => $products, 'classColumn' => 'col-md-4 items mb-4'])->render();
                return response()->json(['status' => true, 'data' => $view, 'paramQuery' => $request->all()]);
            }
        } catch (BadResponseException $badResponse) {
            // session()->put('error', $)
        } catch (ClientException $clientException) {
            // dd($clientException);
        } catch (RequestException $e) {
            // return $e->getPMessage();
        }

        session()->flashInput($request->input());
        return view('frontend::products.search', compact('products', 'brands', 'categories'));
    }

    /**
     *
     */
    public function show($id)
    {
        try {
            $url = 'products/' . $id;
            $url =  ClientService::getBaseUri() . '/' . $url;
            $newresponse = $this->client->request('GET', $url, [
                'headers'  => ClientService::getHeaders(),
                'verify' => false
            ]);
            $response = $newresponse->getBody()->getContents();
            $response = json_decode($response, true);
            
            $variants = [];
            $product = $response['data']['products'][0];
            $shop = $response['data']['shop'][0];
            $seller_products = $response['data']['seller_product'];
            $related_products = $response['data']['related_product'];
            $categories = $response['data']['categories'];

            // if (count($product["variants"]) > 0) {
            //     foreach ($product["variants"] as $key => $variant) {
            //         $variants[$variant['variant_id']] = [
            //             'image_name' => 
            //         ];
            //     }
            // }

            $settings = ['title' => $product['name'], 'description' => $product['description']];
            return view('frontend::products.show', compact('settings', 'categories', 'product', 'shop', 'seller_products', 'related_products'));
        } catch (BadResponseException $badResponse) {
            dd($badResponse);
        } catch (ClientException $clientException) {
            dd($clientException);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     *
     */
    public function getPriceUnit(Request $request)
    {
        if ($request->ajax()) {
            $product_price = ProductPrice::where('product_id', $request->product)
                ->where('unit_id', $request->unit)
                ->with('unit')->first();
            return response()->json([
                'name' => $product_price->unit ? $product_price->unit->name : '',
                'price' => $product_price->buyer
            ]);
        }

        return response()->json(['error' => 401]);
    }

    /**
     *
     */
    public function filter(Request $request)
    {
        $products = [];
        $categories = [];
        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 12;
            $category = $request->ajax() ? \Request::get('cat') : $request->cat;

            $base_uri = env('API_URL') ? env('API_URL') : 'https://api.ganzberg.com/api';
            $url = 'public/products?id=093216&token=GanGosFutureToken2020&page=';
            $url .= $page . '&limit=' . $limit . '&name=' . $request->q .  '&min_price=' . $request->min_price . '&max_price=' . $request->max_price;

            $basicauth = new Client(['base_uri' => $base_uri]);
            $headers = [
                'Content-Type'        => 'application/json',
                'Content-Language'  =>  \App::getLocale()
            ];


            $newresponse = $basicauth->request('GET', '/api/' . $url, $headers)->getBody()->getContents();
            $data = json_decode($newresponse, true);
            $products = $data['data']['products'];
        } catch (BadResponseException $badResponse) {
            dd($badResponse);
        } catch (ClientException $clientException) {
            dd($clientException);
        } catch (RequestException $e) {
            // return $e->getPMessage();
        }

        session()->flashInput($request->input());
        return view('frontend::products.search', compact('products', 'categories', 'category'));
    }
}
