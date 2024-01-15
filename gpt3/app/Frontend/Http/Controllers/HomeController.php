<?php

namespace App\Frontend\Http\Controllers;

use App\Admin\Models\Banner;
use App\Frontend\Helpers\ClientService;
use Illuminate\Http\Request;
use App\Frontend\Models\Product;
use GuzzleHttp\Client;
use App\Frontend\Models\Category;
use App\Frontend\Models\ShoppingCart;
use Illuminate\Support\Facades\Auth;
use Cart;
use Illuminate\Support\Facades\Lang;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $client;
    private $headers;
    private $base_uri;
    private $access_token;


    public function __construct()
    {
        //$this->middleware('auth');
        //$this->client = new Client();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $request->all();
            }
            
            session()->forget('products');
            $url = 'public/home?id=093216&token=GanGosFutureToken2020';
            $basicauth = new Client(['base_uri' => ClientService::getBaseUri()]);
            
            $newresponse = $basicauth->request(
                'GET',
                '/api/' . $url,
                ['headers' => ClientService::getHeaders()]
            )->getBody()->getContents();
            
            $data = json_decode($newresponse, true);
            $new_products = $data['data']['new_products'];
            $promoted_products = $data['data']['promoted_products'];
            $random_products = $data['data']['random_products'];
            $categories = $data['data']['categories'];
            //session()->put('categories', $categories);
            $brands = $data['data']['brands'];
            $banners = $data['data']['banners'];
        } catch (\Exception $e) {
            return $e->getMessage();
            return abort(500, 'Server Have Problem.');
        }

        return view('frontend::home_new', compact('new_products', 'promoted_products', 'random_products', 'categories', 'banners', 'brands'));
    }

    /**
     *
     */
    public function notificaiton()
    {
        try {
            $base_url = env('API_URL');
            $headers = [
                'Authorization' => 'Bearer ' . session()->get('token'),
                'Content-Type'        => 'application/json',
                'Content-Language'  =>  \App::getLocale()
            ];
            $client = new Client();
            $response = $client->request('GET', $base_url . '/notifications', [
                'headers'  => $headers,
                'verify' => false
            ]);

            $data = json_decode($response->getBody(), true);
            $data = $data['data']['notification'];

            return view('frontend::my.account.notification.index', compact('data'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     *
     */
    public function loadData(Request $request)
    {
        $limit = $request->limit ?? 10;
        $page = $request->page ?? 1;
        $product = [];

        try {
            $url = '';
            $previous_route = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();

            if ($previous_route == 'home' || $previous_route == 'search') {
                $url = 'public/products?id=093216&token=GanGosFutureToken2020&';
            }

            if ($previous_route == 'search.category') {
                $url = 'categories/products/' . $request->id . '?';
            }

            if ($previous_route == 'search.brand') {
                $url = 'brands/products/' . $request->id . '?';
            }

            $url .= 'page=' . $page . '&limit=' . $limit;
            $url =  ClientService::getBaseUri() . '/' . $url;
            $newresponse = $this->client->request('GET', $url, [
                'headers'  => ClientService::getHeaders(),
                'verify' => false
            ]);

            $newresponse = $newresponse->getBody()->getContents();
            $data = json_decode($newresponse, true);
            $data = $data['data'];

            if (array_key_exists('products', $data)) {
                $products = $data['products'];
            }

            if (array_key_exists('0', $data)) {
                $products = $data['0']['products'];
            }


            $html = '';
            foreach ($products as $key => $product) {
                $html .= view('frontend::components.product_medium', compact('product'))->render();
            }


            return response()->json([
                'products' => $products,
                'header' => Lang::getLocale(),
                'html'  => $html,
                'request' => [
                    'page' => $page,
                    'limit' => $limit
                ]
            ]);
        } catch (\Exception $err) {
            return response()->json([
                'products' => [],
                'message' => $err->getMessage(),
                'request' => [
                    'page' => $page,
                    'limit' => $limit
                ]
            ]);
        }
        // return $request->all();
    }
}
