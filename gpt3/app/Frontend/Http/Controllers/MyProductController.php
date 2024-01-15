<?php

namespace App\Frontend\Http\Controllers;

use App\Admin\Models\Brand;
use App\Admin\Models\BrandTranslation;
use App\Admin\Models\Category;
use App\Admin\Models\CategoryTranslation;
use App\Admin\Models\Product;
use App\Admin\Models\ProductImage;
use App\Admin\Models\ProductPrice;
use App\Admin\Models\ProductTranslation;
use App\Admin\Models\Unit;
use App\Admin\Models\UnitTranslation;
use App\Frontend\Models\CityProvince;
use App\Frontend\Models\CityProvinceTranslate;
// use App\Frontend\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;

class MyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $products = [];
        $page = $request->page ?? 1;
        $base_url = env('API_URL');
        $headers = [
            'Authorization' => 'Bearer ' . session()->get('token'),
            'Content-Type'        => 'application/json',
            'Content-Language'  =>  \App::getLocale()
        ];

        try {
            $client = new Client();
            $response = $client->request('GET', $base_url . '/shop/product-lists?page=' . $page, [
                'headers'  => $headers,
                'verify' => false
            ]);
            $response = json_decode($response->getBody(), true);
            // return $response;
            if ($response['status']) {
                $products = $response['data']['products'];
            }

            if ($request->ajax()) {
                $view = view('frontend::my.account.products.products', compact('products'))->render();
                return response()->json(['status' => true, 'data' => $view], 200);
            }

            return view('frontend::my.account.products.index', compact('products'));
        } catch (\Exception $e) {
            return $e->getMessage();
            // \Auth::logout();
            return redirect('/login');
        }
    }

    public function addProduct()
    {
        $locale = \App::getLocale();
        $categories = CategoryTranslation::where('locale', $locale)->pluck('name', 'category_id')->toArray();
        $units = UnitTranslation::where('locale', $locale)->pluck('name', 'unit_id')->toArray();
        $brands = BrandTranslation::where('locale', $locale)->pluck('name', 'brand_id')->toArray();
        $cities = CityProvinceTranslate::where('locale', $locale)->pluck('name', 'city_province_id')->toArray();

        return view('frontend::my.account.products.add_product', compact('categories', 'units', 'brands', 'cities'));
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
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'qty' => 'required',
            'distributor' => 'required',
            'wholesaler' => 'required',
            'retailer' => 'required',
            'buyer' => 'required',
            'description' => 'required',
            'images'    =>  'required'
        ]);

        if (auth()->user()->shop_id == 0 || !auth()->user()->shop_id) {
            return \Redirect::back()->withErrors(['shop', 'Your Shop is have not approve yet, Please wait until your shop have been approve']);
        }

        $base_url = env('API_URL');
        try {
            $token = session()->get('token');

            $requests = [
                'user_id'   =>  auth()->user()->id,
                'shop_id'   =>  auth()->user()->shop_id,
                'name' => $request->name,
                'product_code'  =>  $request->product_code,
                'locale' => $request->locale,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'unit' => $request->unit_id,
                'city_id'   => $request->city_id,
                'unit_qty' => $request->qty,
                'distributor' => $request->distributor,
                'wholesaler' => $request->wholesaler,
                'retailer' => $request->retailer,
                'buyer' => $request->buyer,
                'description' => $request->description,
                'images'    =>  $request->images,
                'price_flag'    =>  $request->price_flag
            ];

            $client = new Client(["base_uri" => $base_url . '/products/create']);

            $response = $client->request("POST", "", [
                'headers'  => [
                    'authorization' => 'Bearer ' . $token
                ],
                'json'  =>  $requests,
                'verify' => false,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            if (count($data) > 0) {
                if ($data['status']) {
                    if ($request->hasFile('images')) {
                        foreach ($request->images as $image) {
                            if (!is_null($image)) {
                                $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                                $path = public_path('/uploads/images/products');
                                $image->move($path, $filename);
                                ProductImage::create([
                                    'product_id' => $data['data']['product']['id'],
                                    'image_name' => $filename
                                ]);
                            }
                        }
                    }
                }
            }

            \Flash::success('Product saved successfully.');
            return redirect(route('my-products.index'));
        } catch (Exception $e) {
            return \Redirect::back()->withErrors($e->getMessage())->withInput();
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
        $edit = 1;
        $product = Product::find($id);
        $locale = \App::getLocale();
        $categories = CategoryTranslation::where('locale', $locale)->pluck('name', 'category_id')->toArray();
        $units = UnitTranslation::where('locale', $locale)->pluck('name', 'unit_id')->toArray();
        $brands = BrandTranslation::where('locale', $locale)->pluck('name', 'brand_id')->toArray();
        $cities = CityProvinceTranslate::where('locale', $locale)->pluck('name', 'city_province_id')->toArray();
        $product_price = ProductPrice::where('product_id', $id)->get();
        $product_translation = ProductTranslation::where('product_id', $id)->get();
        $images = ProductImage::where('product_id', $id)->pluck('image_name', 'id');

        return view(
            'frontend::my.account.products.edit_product',
            compact(
                'categories',
                'units',
                'brands',
                'cities',
                'edit',
                'product',
                'product_price',
                'product_translation',
                'images'
            )
        );
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
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'qty' => 'required',
            'distributor' => 'required',
            'wholesaler' => 'required',
            'retailer' => 'required',
            'buyer' => 'required',
            'description' => 'required',
            'images' => $request->old ? '' : 'required'
        ]);

        if (auth()->user()->shop_id == 0 || !auth()->user()->shop_id) {
            return \Redirect::back()->withErrors(['shop', 'Your Shop is have not approve yet, Please wait until your shop have been approve']);
        }

        $base_url = env('API_URL');
        try {
            $token = session()->get('token');

            $requests = [
                'user_id'   =>  auth()->user()->id,
                'shop_id'   =>  auth()->user()->shop_id,
                'name' => $request->name,
                'product_code'  =>  $request->product_code,
                'locale' => $request->locale,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'unit' => $request->unit_id,
                'city_id'   => $request->city_id,
                'unit_qty' => $request->qty,
                'distributor' => $request->distributor,
                'wholesaler' => $request->wholesaler,
                'retailer' => $request->retailer,
                'buyer' => $request->buyer,
                'description' => $request->description,
                'images'    =>  $request->images,
                'old'   =>  $request->old ? $request->old : null,
                'price_flag'    =>  $request->price_flag
            ];

            $client = new Client(["base_uri" => $base_url . '/products/update/' . $id]);

            $response = $client->request("PUT", "", [
                'headers'  => [
                    'authorization' => 'Bearer ' . $token
                ],
                'json'  =>  $requests,
                'verify' => false,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            if (count($data) > 0) {
                if ($data['status']) {
                    if ($request->hasFile('images')) {
                        foreach ($request->images as $image) {
                            if (!is_null($image)) {
                                $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                                $path = public_path('/uploads/images/products');
                                $image->move($path, $filename);
                                ProductImage::create([
                                    'product_id' => $data['data']['product']['id'],
                                    'image_name' => $filename
                                ]);
                            }
                        }
                    }
                }
            }

            $path = public_path('/uploads/images/products');
            $image = isset($request->old) ? ProductImage::where('product_id', $id)->whereNotIn('id', $request->old) :
                ProductImage::where('product_id', $id);

            foreach ($image->get() as $key => $value) {
                $image_path = $path . '/' . $value->image_name;
                if (file_exists($image_path))
                    unlink($image_path);
            }
            $image->delete();

            if (isset($request->images)) {
                foreach ($request->images as $image) {
                    $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();

                    $image->move($path, $filename);
                    ProductImage::create([
                        'product_id' => $id,
                        'image_name' => $filename
                    ]);
                }
            }


            \Flash::success('Product saved successfully.');

            return redirect(route('my-products.index'));
        } catch (Exception $e) {
            return \Redirect::back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $product = Product::find($id);

        if ($product) {
            $images = ProductImage::where('product_id', $id)->get();
            if ($images) {
                foreach ($images as $key => $value) {
                    $image_path = public_path('/uploads/images/products/' . $value->image_name);
                    if (file_exists($image_path))
                        unlink($image_path);
                }
            }
            ProductImage::where('product_id', $id)->delete();
            ProductTranslation::where('product_id', $id)->delete();
            $product->delete($id);

            if ($request->ajax()) {
                return response()->json(['status' =>  true,  'message'   =>  'Product Deleted Successfully!']);
            }
        }
    }
}
