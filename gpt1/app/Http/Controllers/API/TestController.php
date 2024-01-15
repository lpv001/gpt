<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductPrice;
use App\ProductImage;
use App\Helper\ImageHandler;

use App\Category;
use Respone;
use DB;
use Validator;


class TestController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;
    
    /**
     * 
     */
    private function updateCategories() {
        $languages = ['en', 'km'];
        $rows = DB::table('categories')->select('*')->get();
        
        foreach ($languages as $locale) {
            foreach ($rows as $r) {
                DB::table('category_translations')->insert(['category_id' => $r->id, 'name' => $r->default_name, 'locale' => $locale]);
            }
        }
    } //EOF
    
    /**
     * 
     */
    private function updateBrands() {
        $languages = ['en', 'km'];
        $rows = DB::table('brands')->select('*')->get();
        
        foreach ($languages as $locale) {
            foreach ($rows as $r) {
                DB::table('brand_translations')->insert(['brand_id' => $r->id, 'name' => $r->name, 'locale' => $locale]);
            }
        }
    } //EOF

    /**
     * 
     */
    private function updateUnits() {
        $languages = ['en', 'km'];
        $rows = DB::table('units')->select('*')->get();
        
        foreach ($languages as $locale) {
            foreach ($rows as $r) {
                DB::table('unit_translations')->insert(['unit_id' => $r->id, 'name' => $r->name, 'locale' => $locale]);
            }
        }
    } //EOF

    /**
     * 
     */
    private function updateProducts() {
        $languages = ['en', 'km'];
        $rows = DB::table('products')->select('*')->get();
        
        foreach ($languages as $locale) {
            foreach ($rows as $r) {
                DB::table('product_translations')->insert(['product_id' => $r->id, 'name' => $r->name, 'description' => $r->description, 'locale' => $locale]);
            }
        }
    } //EOF


    /**
     * 
     */
    public function updateAll1(Request $request){
        try{
            $this->updateCategories();
            $this->updateBrands();
            $this->updateUnits();
            $this->updateProducts();
            $response = [
                        'status'  => true,
                        'msg' => 'Get products successfully',
                        'data' => ["ffff"]
                    ];
            return response()->json($response, 200);

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
     * 
     */
    private function insertCategoryProduct() {
        dd("DONE");
        $rows = DB::table('products')->select('*')->get();
        
        foreach ($rows as $r) {
            DB::table('category_product')->insert(['category_id' => $r->category_id, 'product_id' => $r->id]);
        }
    } //EOF

    /**
     * 
     */
    private function insertShopUser() {
        dd("DONE");
        $rows = DB::table('shops')->select('*')->get();
        
        foreach ($rows as $r) {
            DB::table('shop_user')->insert(['shop_id' => $r->id, 'user_id' => $r->user_id]);
        }
    } //EOF

    /**
     * 
     */
    public function viewLogs(Request $request){
      dd("jjjjj");
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
            return response()->json($response, 401);
        }
        
        try{
            $row = DB::table('system_logs')->select('*')->where('id', $request->input('logid'))->first();
            $response = [
                        'status'  => true,
                        'msg' => 'Update successfully.',
                        'data' => ['log' => json_decode($row->logs)]
                    ];
            return response()->json($response, 200);

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
     * 
     */
    private function updateProductPrice() {
        dd("DONE");
        $rows = DB::table('product_prices')->select('*')->where('flag', 1)->get();
        
        foreach ($rows as $r) {
            DB::table('products')->where('id', DB::raw($r->product_id))->update([
                'unit_price' => $r->buyer,
                'sale_price' => $r->buyer,
                'unit_id' => $r->unit_id,
                'shop_id' => 1,
                'user_id' => 1
                ]);
       }
    } //EOF


    /**
     * 
     */
    public function execute(Request $request){
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
            return response()->json($response, 401);
        }
        
        try{
            $this->updateProductPrice();
            
            $response = [
                        'status'  => true,
                        'msg' => 'Update successfully.',
                        'data' => ["ffff"]
                    ];
            return response()->json($response, 200);

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
     *
     */
    public function modifyProductImages()
    {
        try {
            $images = ProductImage::where('updateflag', 0)->skip(0)->take(100)->get();
            if (count($images) < 1) {
                dd("DONE");
            }
            
            $image_handler = new ImageHandler;
            foreach ($images as $key => $value) {
              $image_path = public_path('/uploads/images/products') . '/' . $value->image_name;
              if (file_exists($image_path)) {
                $image_handler->createThumbnailNoneCrop(public_path('/uploads/images/products'), $value->image_name);
                ProductImage::where('id', $value->id)->update(['updateflag' => 1]);
              }
            }
            
            $response = [
                'status' => true,
                'msg' => 'Update images Successfully',
                'data'  =>  []
            ];
            return response()->json($response, $this->successCreated);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    }
}
