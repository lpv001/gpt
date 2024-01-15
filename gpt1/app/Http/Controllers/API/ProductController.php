<?php

namespace App\Http\Controllers\API;

use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductPrice;
use App\ProductImage;
use App\Category;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductListCollection;
use App\Http\Resources\ProductCollectionResource;
use App\Http\Resources\ProductListCollectionResource;
use App\Http\Resources\CategoriesResource;

use App\Helper\ImageHandler;
use App\Http\Requests\UpdateProductRequest;
use App\Option;
use App\OptionValue;
use App\ProductOption;
use App\ProductTranslation;
use App\Shop;
use App\SystemLog;
use App\Variant;
use App\VariantOptionValue;
use Respone;
use DB;
use Exception;
use Illuminate\Support\Facades\DB as FacadesDB;
use Validator;

class ProductController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;


    /**
     *
     */
    public function getProductsByLocation(Request $request, $id)
    {
        $limit = $request->input('limit', 12);
        $page = $request->input('page', 1);


        try {
            // Get shops by this location
            $shops = Shop::where('city_province_id', $id)->get();
            $shop_list = [];
            $shop_ids = [];
            if (count($shops) > 0) {
                foreach ($shops as $s) {
                    $shop_list[$s->id] = $s;
                }
                $shop_ids = array_keys($shop_list);
            }

            $products = Product::orderBy('id', 'DESC')->skip((intval($page) - 1) * intval($limit))->take(intval($limit))
                ->with('shop')->with('image')
                ->where('is_active', 1)
                ->whereIn('shop_id', $shop_ids)->get();
            // Work on products resource
            if (count($products) > 0) {
                foreach ($products as $k => $p) {
                    $p->shop_name = $p->shop->name;
                    $image_name = 'default.png';
                    if ($p->image) {
                        $image_name = $p->image->image_name;
                    }
                    $p->image_name = env('PUB_URL') . '/uploads/images/products/thumbnail/medium_' . $image_name;
                    $products[$k] = $p;
                }
            }

            /*
        $product = new Product;
        $params = array('shop_list' => $shop_ids, 'load' => 'order_products');
        $products = $product->getProductList($params, $limit, $page);
        */

            $response = [
                'status'  => true,
                'msg' => 'Get products successfully',
                'data' => [
                    'shops' => $shop_list,
                    'products' => $products,
                    'request' => ['limit' => $limit, 'page' => $page]
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
     * Display a listing of the product.
     * api/public/products
     * @return \Illuminate\Http\Response
     */
    public function getPublicProductList(Request $request)
    {
        $limit = $request->input('limit', 12);
        $page = $request->input('page', 1);
        $load = $request->input('load', 'random_products');
        $channel = $request->input('channel', 'new');

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

        $data = [
            'limit' => $limit,
            'page'  => $page
        ];

        if ($load == 'random_products') {
            $products = Product::inRandomOrder()->skip((intval($page) - 1) * intval($limit))->take(intval($limit))->where('is_active', 1);
        } else {
            $products = Product::orderBy('id', 'DESC')->skip((intval($page) - 1) * intval($limit))->take(intval($limit))->where('is_active', 1);
        }

        if ($channel == 'promoted') {
            $products->where('is_promoted', 2);
        }

        if (!empty($request->input('category_id'))) {
            $products->where('category_id', $request->input('category_id'));
        }

        if (!empty($request->input('shop_id'))) {
            $products->where('shop_id', $request->input('shop_id'));
        }

        $products = $products->get();

        return (new ProductListCollectionResource($products))->request($data);
    } //EOF

    /**
     *
     */
    public function getProductListTODELETE(Request $request, $category_id = null)
    {
        $limit = $request->input('limit', 12);
        $page = $request->input('page', 1);

        try {
            // Get list of sub-categories if category_id is not null
            $sub_categories = null;
            $category = new Category;
            if ($category_id != null) {
                //$sub_categories = $category->getListofSubCategory($category_id);
                $sub_categories = $category->getCategories($category_id);
            }

            $product = new Product;
            // Get base product price list
            $params = array('category_id' => $category_id, 'brand_id' => null);
            $products = $product->getProductList($params, $limit, $page);

            $response = [
                'status'  => true,
                'msg' => 'Get products successfully',
                'data' => [
                    'categories' => $sub_categories,
                    'products' => $products,
                    'request' => ['limit' => $limit, 'page' => $page]
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
    } //EOF

    /**
     *
     */
    public function getShopProductList(Request $request, $shop_id = null)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        try {
            $product = new Product;
            // Get base product price list
            $params = array('category_id' => null, 'brand_id' => null, 'shop_id' => $shop_id);
            $products = $product->getProductList($params, $limit, $page);

            $response = [
                'status'  => true,
                'msg' => 'Get products successfully',
                'data' => [
                    'products' => $products,
                    'request' => ['limit' => $limit, 'page' => $page]
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
    } //EOF

    /**
     * api/brands/products/{brand_id?}
     */
    public function getProductsByBrand(Request $request, $brand_id = null)
    {
        $limit = $request->input('limit', 12);
        $page = $request->input('page', 1);

        try {
            $qry = Product::where('is_active', 1)->with('shop')
                ->orderBy('id', 'DESC')
                ->skip(($page - 1) * $limit)->take($limit);

            if ($brand_id > 0) {
                $qry->where('brand_id', $brand_id);
            }

            $products = $qry->get();
            /*
          $product = new Product;
          $params = array('brand_id' => $brand_id, 'category_id' => null);
          $products = $product->getProductList($params, $limit, $page);
          */
            $response = [
                'status'  => true,
                'msg' => 'Get products successfully',
                'data' => [
                    'products' => ProductListCollection::collection($products),
                    'request' => ['limit' => $limit, 'page' => $page]
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
    } //EOF

    /**
     * New function the same as getDetailProduct()
     * Call from api/products/{product_id}
     * @return \Illuminate\Http\Response
     */
    public function getProductDetail($product_id)
    {
        $product = Product::where('id', $product_id)
            ->with([
                'shop',
                'product_option.option.option_value' => function ($query) {
                    return $query->select('id', 'name', 'option_id');
                },
                'product_option.option.option_value.image',
                'variant_option.variant',
                'images' => function ($query) {
                    return $query->select('id', 'product_id', 'image_name');
                }
            ])->get();

        // indicate the ProductCollection that it is detail view
        $product[0]->view = 'detail';

        return (new ProductCollectionResource($product))->response();
    } //EOF

    /**
     * Return true if the user is a approved seller
     */
    private function validateSeller()
    {
        if (Auth::user()->shop == null) {
            return false;
        } else {
            $shop = Auth::user()->shop;
            if ($shop->id > 0 && $shop->status < 1) {
                return false;
            }
        }

        return true;
    } // EOF

    /**
     *
     */
    public function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit' => 'required',
            'buyer' => 'required'
        ]);

        return $validator;
    } // EOF

    /**
     * Create/Add new product into product
     *
     */
    public function createProduct(Request $request)
    {
        $validator = $this->validateRequest($request);

        // DEBUG ONLY
        if (env('SYS_LOG') == true) {
            $system_log = new SystemLog;
            $system_log->module = 'createProduct';
            $system_log->logs = json_encode($request->all());
            $system_log->save();
        }

        if ($validator->fails()) {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }

        try {
            if (!$this->validateSeller()) {
                //if (!auth()->user()->shop_id && auth()->user()->shop_id === 0) {
                $message = 'Your shop has not been approved yet.';
                $response = [
                    'status' => false,
                    'msg' => $message,
                    'data' => [],
                ];
                return response()->json($response, 401);
            }

            $shop = Auth::user()->shop;
            $product = Product::create([
                'user_id'   =>  auth()->user()->id,
                'shop_id'   =>  $shop->id,
                'product_code'  =>  $request->product_code,
                'name'  =>  'no used',
                'unit_price'  =>  0,
                'sale_price'  =>  0,
                'point_rate'  =>  0,
                'description'  =>  'no used',
                'unit_id'  =>  0,
                'category_id'  =>  $request->category_id,
                'brand_id'  =>  $request->brand_id,
                'flag'  =>  0,
                'is_active'  =>  1,
                'is_promoted'  => 1,
            ]);

            // Product translations
            $default_languages = ['en' => 'English', 'km' => 'Khmer'];
            $default_language = array();
            foreach ($request->name as $k => $value) {
                $locale = $request->locale[$k];
                unset($default_languages[$locale]);

                ProductTranslation::create([
                    'product_id'    =>  $product->id,
                    'name'          =>  $request->name[$k],
                    'description'   =>  $request->description[$k],
                    'locale'        =>  $locale,
                ]);
                // assign default language
                $default_language = [
                    'product_id'    =>  $product->id,
                    'name'          =>  $request->name[$k],
                    'description'   =>  $request->description[$k],
                    'locale'        =>  $locale,
                ];
            }

            foreach ($default_languages as $locale => $value) {
                ProductTranslation::create([
                    'product_id'    =>  $default_language['product_id'],
                    'name'          =>  $default_language['name'],
                    'description'   =>  $default_language['description'],
                    'locale'        =>  $locale,
                ]);
            }

            // Product images
            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    if (!is_null($image)) {
                        $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                        $path = public_path('/uploads/images/products');
                        $image->move($path, $filename);
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_name' => $filename
                        ]);
                    }
                }
            }

            // Product prices
            foreach ($request->unit as $k => $value) {
                ProductPrice::create([
                    'product_id' => $product->id,
                    'type_id' => 0,
                    'unit_id' => $request->unit[$k],
                    'city_id' => $request->city_id[$k] ?? 0,
                    'qty_per_unit' => $request->unit_qty[$k],
                    'unit_price' => 0,
                    'sale_price' => 0,
                    'distributor' => $request->distributor[$k],
                    'wholesaler' => $request->wholesaler[$k],
                    'retailer' => $request->retailer[$k],
                    'buyer' => $request->buyer[$k],
                    'flag' => $request->price_flag[$k] ?? 0,
                ]);
            }

            $product = Product::where('id', $product->id)
                ->with('images', 'prices')->first();

            $response = [
                'status' => true,
                'msg' => 'Upload product successfully',
                'data' => ['product' => $product]
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

    /**
     * Store a newly update Product in storage.
     *
     * @param UpdateProductRequest $request
     *
     * @return Response
     */
    public function updateProduct(UpdateProductRequest $request, $id)
    {
        try {
            if (!$this->validateSeller()) {
                $message = 'You have not yet become seller.';
                $response = [
                    'status' => false,
                    'msg' => $message,
                    'data' => [],
                ];
                return response()->json($response, 400);
            }

            $product = Product::find($id);
            if (!$product)
                return response()->json([
                    'status' => false,
                    'msg' => 'Product not found',
                    'data'  =>  []
                ], 404);

            DB::beginTransaction();

            // Update product
            Product::where('id', $id)->update([
                'name' => $request->name,
                'unit_price' => $request->price,
                'sale_price' => $request->price,
                'product_code'  =>  $request->code,
                'category_id'  =>  $request->category_id,
                'brand_id'  =>  $request->brand_id,
                'description' => $request->description,
            ]);

            // Product translations
            $default_languages = ['en' => 'English', 'km' => 'Khmer'];
            foreach ($default_languages as $k => $value) {
                ProductTranslation::where('product_id', $id)->update([
                    'name'          =>  $request->name,
                ]);
            }

            $image_handler = new ImageHandler;
            $path = public_path('/uploads/images/products');

            if ($request->has('delete_images') && count($request->delete_images) > 0) {
                foreach ($request->delete_images as $deleteImageId) {
                    $delete_image = ProductImage::find($deleteImageId);
                    if ($delete_image) {
                        $imageSizes = ['small', 'medium', 'large'];
                        foreach ($imageSizes as  $size) {
                            $oldImagePath = $path . "/thumbnail/" . $size . "_" . $delete_image->image_name;
                            if (file_exists($oldImagePath)) {
                                unlink($oldImagePath);
                            }
                        }
                        $delete_image->delete();
                    }
                }
            }


            if ($request->has('images') && count($request->images) > 0)
                foreach ($request->images as $image) {
                    if (array_key_exists('file', $image)) {
                        $is_cropped = 1;
                        $filename = $product->id . '_' . uniqid() . time() . '.' . $image['file']->getClientOriginalExtension();
                        $image['file']->move($path, $filename);
                        if ($request->input('enableCropImage')) {
                            $image_handler->createThumbnail($path, $filename);
                        } else {
                            $is_cropped = 0;
                            $image_handler->createThumbnailNoneCrop($path, $filename);
                        }

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_name' => $filename
                        ]);
                    }
                }

            // update Option
            $optionValues = [];

            //
            $optionIdsNotDelete = []; // used to store id of option that will be update otherwise will be
            $optionValueIdsNotDeleteValue = [];
            //
            if (!is_null($request->options) && count($request->options) > 0) {
                foreach ($request->options as $key => $requestOption) {
                    $optionId = null;
                    // find and Update Option Model
                    if (array_key_exists('id', $requestOption)) {
                        $optionId = $requestOption['id'];
                        $option = Option::find($optionId);
                        if ($option) {
                            $option->update(['name' => $requestOption['name']]);
                        }
                    } else { // else will create new one
                        $option = Option::create(['name' => $requestOption['name']]);
                        $optionId = $option->id;
                        ProductOption::create([
                            'option_id' => $optionId,
                            'product_id' => $product->id,
                        ]);
                    }
                    $optionIdsNotDelete[] = $optionId;

                    // option value request
                    if (!is_null($requestOption['optionValues']) && count($requestOption['optionValues']) > 0) {
                        foreach ($requestOption['optionValues'] as $key => $requestOptionValue) {

                            $optionValueId = null;
                            // check if request have id then will update
                            if (array_key_exists('id', $requestOptionValue)) {
                                $optionValueId = $requestOptionValue['id'];
                                $optionValue = OptionValue::find($optionValueId);

                                if ($optionValue) {
                                    $optionValue->update(['name' => $requestOptionValue['name']]);
                                }
                            } else { // if not have id then will create new one

                                $optionValue = OptionValue::create([
                                    'option_id' => $optionId,
                                    'product_id' => $product->id,
                                    'name' => $requestOptionValue['name'],
                                    'boid' => $optionId,
                                ]);
                                $optionValueId = $optionValue->id;
                            }
                            $optionValueIdsNotDeleteValue[] = $optionValueId;


                            // check if option have each image
                            if (array_key_exists('image', $requestOptionValue) && !is_null($requestOptionValue['image'])) {
                                $filename = $product->id . '_' . uniqid() . time() . '.' . $requestOptionValue['image']->getClientOriginalExtension();
                                if ($request->input('enableCropImage')) {
                                    $image_handler->createThumbnail($path, $filename);
                                } else {
                                    $is_cropped = 0;
                                    $image_handler->createThumbnailNoneCrop($path, $filename);
                                }
                            }

                            $optionValues[$requestOptionValue['name']] = $optionValueId;
                        }
                    }
                }


                //
                // find the id of old option that will not be delete
                if (count($optionIdsNotDelete) > 0) {
                    $deleteProductOption = ProductOption::where('product_id', $id)->whereNotIn('option_id', $optionIdsNotDelete);
                    $optionIds = $deleteProductOption->pluck('option_id');
                    if (count($optionIds)) {
                        Option::whereIn('id', $optionIds)->delete();
                        $deleteProductOption->delete();
                    }

                    // optionValue find the id of old option value that will not be delete
                    $delOptionValues = OptionValue::whereNotIn('id', $optionValueIdsNotDeleteValue);
                    if ($delOptionValues) {
                        $delOptionValues->delete();
                    }
                }
            }


            //
            // check any product variant request
            if (!is_null($request->variants) && count($request->variants) > 0) {
                foreach ($request->variants as $key => $requestVariant) {
                    $variantId = null;
                    if (array_key_exists('id', $requestVariant)) {
                        $variantId = $requestVariant['id'];
                        $variant = Variant::find($variantId);

                        if ($variant)
                            $variant->update(['price' => $requestVariant['price']]);
                    } else {
                        $variant = Variant::create([
                            'sku' => 0,
                            'product_id' => $product->id,
                            'name' => $requestVariant['option_name'],
                            'price' => $requestVariant['price']
                        ]);

                        if (array_key_exists($requestVariant['option_name'], $optionValues)) {
                            VariantOptionValue::create([
                                'variant_id' => $variant->id,
                                'product_id' => $product->id,
                                'option_value_id' => $optionValues[$requestVariant['option_name']],
                            ]);
                        }
                    }
                }
            }
            DB::commit();

            //
            $product =  Product::where('id', $product->id)->with([
                'product_option.option.option_value',
                'variant_option',
            ])->get();

            $data = ProductCollection::collection($product);

            $response = [
                'status' => true,
                'msg' => 'Update product successfully',
                'data'  => [
                    'product' => $data
                ]
            ];
            return response()->json($response, $this->successCreated);
        } catch (Exception $e) {
            DB::rollback();
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
    public function deleteProduct($id)
    {
        try {
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
                ProductPrice::where('product_id', $id)->delete();
                $product->delete($id);
                $response = [
                    'status' => true,
                    'msg' => 'Product Deleted Successfully',
                    'data'  =>  []
                ];
                return response()->json($response, $this->successCreated);
            }
        } catch (Exception $e) {
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
    private function productOption(Request $request, $product)
    {
        try {
            $option = [];
            $option_value_id = [];
            $index = 0;

            ProductOption::where('product_id', $product->id)->delete();
            if (isset($request->options) && ($request->options[0] != null)) {
                foreach ($request->options as $key => $option) {
                    $index = ++$key;
                    $option = Option::create([
                        'name'  =>  $option
                    ]);

                    $product_option = ProductOption::create([
                        'product_id'    =>  $product->id,
                        'option_id'     =>  $option->id
                    ]);

                    $request_option_value = 'option_values_' . $index;
                    if (count($request->$request_option_value) > 0) {
                        foreach ($request->$request_option_value as $key => $item_option_value) {
                            $option_value = OptionValue::create([
                                'option_id' => $option->id,
                                'name'  =>  $item_option_value
                            ]);
                            $option_value_id[$item_option_value] = $option_value->id;
                        }
                    }
                }
            }

            if (isset($request->option_value_delete_image_id)) {
                foreach ($request->option_value_delete_image_id as $key => $delete_id) {
                    if ($delete_id) {
                        $_image = ProductImage::find($delete_id);
                        if ($_image) {
                            $image_path = $this->path . '/' . $_image->image_name;
                            if (file_exists($image_path)) {
                                unlink($image_path);
                                deleteThumbnail($this->path, $_image->image_name);
                            }
                            $_image->delete();
                        }
                    }
                }
            }

            // This is update option value images
            if (isset($request->option_value_old_image_id)) {
                foreach ($request->option_value_images as $key => $value) {
                    if (array_key_exists($value, $option_value_id)) {
                        ProductImage::where('id', $request->option_value_old_image_id[$key])->update([
                            'option_value_id'   =>  $option_value_id[$value]
                        ]);
                    }
                }
            }

            $images = [];
            if (isset($request->option_images) && !isset($request->add_option_value_images)) {
                foreach ($request->option_value_images as $key => $value) {
                    if (array_key_exists($value, $option_value_id)) {
                        if (array_key_exists($key, $request->option_images)) {
                            $images[$value] = $request->option_images[$key];
                            $filename = 'op' . $product->id . '_' . uniqid() . time() . '.' . $request->option_images[$key]->getClientOriginalExtension();
                            $request->option_images[$key]->move($this->path, $filename);
                            createThumbnail($this->path, $filename);
                            $product_image = ProductImage::create([
                                'product_id' => $product->id,
                                'option_value_id' => $option_value_id[$value],
                                'image_name' => $filename,
                            ]);
                        }
                    }
                }
            }

            // Add on option value images
            if (isset($request->option_images) && isset($request->add_option_value_images)) {
                foreach ($request->add_option_value_images as $key => $value) {
                    if (array_key_exists($value, $option_value_id)) {
                        if (array_key_exists($key, $request->option_images)) {
                            $images[$value] = $request->option_images[$key];
                            $filename = 'op' . $product->id . '_' . uniqid() . time() . '.' . $request->option_images[$key]->getClientOriginalExtension();
                            $request->option_images[$key]->move($this->path, $filename);
                            createThumbnail($this->path, $filename);
                            $product_image = ProductImage::create([
                                'product_id' => $product->id,
                                'option_value_id' => $option_value_id[$value],
                                'image_name' => $filename,
                            ]);
                        }
                    }
                }
            }

            $count = [];
            $index_images = 0;
            if (!isset($request->variant_id)) {
                Variant::where('product_id', $product->id)->delete();
                VariantOptionValue::where('product_id', $product->id)->delete();
            }

            if (isset($request->varient)) {
                Variant::where('product_id', $product->id)->delete();
                VariantOptionValue::where('product_id', $product->id)->delete();
                foreach ($request->varient as $key_variant => $varient_item) {
                    $varient = Variant::create([
                        'product_id'    =>  $product->id,
                        'name'          =>  $product->name,
                        'price'         =>  $request->varient_price[$key_variant],
                        'sku'           =>  0,
                        'stock'         =>  0,
                    ]);

                    $varient_item = explode(',', $varient_item);
                    foreach ($varient_item as $key => $item_option) {;
                        VariantOptionValue::updateOrCreate([
                            'product_id'        =>  $product->id,
                            'variant_id'        =>  $varient->id,
                            'option_value_id'   =>  array_key_exists($item_option, $option_value_id) ? $option_value_id[$item_option] : 0
                        ], []);
                    }
                }
            }

            return $count;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(ProductStoreRequest $request)
    {
        try {
            if (!$this->validateSeller()) {
                $message = 'Your shop has not been approved yet.';
                $response = [
                    'status' => false,
                    'msg' => $message,
                    'data' => [],
                ];
                return response()->json($response, 400);
            }


            DB::beginTransaction();
            //
            $shop = Auth::user()->shop;
            $product = new Product;
            $product->user_id = Auth::user()->id;
            $product->shop_id = $shop->id;
            $product->name = $request->name;
            $product->product_code = $request->product_code;
            $product->description = $request->description ?? '';
            $product->unit_price = $request->price;
            $product->sale_price = $request->price;
            $product->point_rate = 0;
            $product->unit_id = $request->unit_id ?? 0;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            $product->flag = 0;
            $product->is_active = 1;
            $product->is_promoted = isset($request->is_promoted) ? 1 : 0;
            $product->save();

            // Product translations
            $default_languages = ['en' => 'English', 'km' => 'Khmer'];
            foreach ($default_languages as $k => $value) {
                ProductTranslation::create([
                    'product_id'    =>  $product->id,
                    'name'          =>  $request->name,
                    'description'   =>  '',
                    'locale'        =>  $k,
                ]);
            }

            // Product Images
            $image_handler = new ImageHandler;
            $path = public_path('/uploads/images/products');
            if (is_null($request->images)) throw new Exception('Image must have at least one image');

            foreach ($request->images as $image) {
                $is_cropped = 1;
                $filename = $product->id . '_' . uniqid() . time() . '.' . $image['file']->getClientOriginalExtension();
                $image['file']->move($path, $filename);
                if ($request->input('enableCropImage')) {
                    $image_handler->createThumbnail($path, $filename);
                } else {
                    $is_cropped = 0;
                    $image_handler->createThumbnailNoneCrop($path, $filename);
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_name' => $filename
                ]);
            }

            // create Option
            $optionValues = [];
            if (!is_null($request->options) && count($request->options) > 0) {
                foreach ($request->options as $key => $value) {
                    $option = new Option();
                    $option->name = $value['name'];
                    $option->save();

                    ProductOption::create([
                        'option_id' => $option->id,
                        'product_id' => $product->id,
                    ]);

                    if (!is_null($value['optionValues']) && count($value['optionValues']) > 0) {
                        foreach ($value['optionValues'] as $key => $optionValue) {
                            $data = OptionValue::create([
                                'product_id' => $product->id,
                                'option_id' => $option->id,
                                'boid' => $option->id,
                                'name' => $optionValue['name'],
                            ]);



                            if (array_key_exists('image', $optionValue) && !is_null($optionValue['image'])) {
                                $optionFilename = uniqid() . time() . '.' . $optionValue['image']->getClientOriginalExtension();
                                $optionValue['image']->move($path, $optionFilename);
                                // createThumbnail($this->path, $filename);
                                $pp = ProductImage::create([
                                    'product_id' => $product->id,
                                    'option_value_id' => $data->id,
                                    'image_name' => $optionFilename
                                ]);
                            }

                            $optionValues[$optionValue['name']] = $data->id;
                        }
                    }
                }
            }

            if (!is_null($request->variants) && count($request->variants) > 0) {
                foreach ($request->variants as $key => $value) {
                    $variant = Variant::create([
                        'sku' => 0,
                        'product_id' => $product->id,
                        'name' => $value['option_name'],
                        'price' => $value['price']
                    ]);

                    if (array_key_exists($value['option_name'], $optionValues)) {
                        VariantOptionValue::create([
                            'variant_id' => $variant->id,
                            'product_id' => $product->id,
                            'option_value_id' => $optionValues[$value['option_name']],
                        ]);
                    }
                }
            }

            //
            $product =  Product::where('id', $product->id)->with([
                'product_option.option.option_value',
                'variant_option',
            ])->get();

            $data = ProductCollection::collection($product);
            DB::commit();

            $response = [
                'status' => true,
                'msg' => 'Upload product successfully',
                'data' => ['product' => $data]
            ];

            return response()->json($response, $this->successCreated);
        } catch (Exception $e) {
            DB::rollback();
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 500);
        }
    }

    /**
     *
     */
    public function show($id)
    {
        $product =  Product::where('id', $id)->with([
            'product_option.option.option_value',
            'variant_option',
            // 'product_option.option.option_value' =>  function ($query) {
            //     return $query->select('id', 'name', 'option_id');
            // },
            // 'variant_option.variant' => function ($query) {
            //     // return $query->select('product_id');
            // }
        ])->get();

        return (new ProductCollectionResource($product))->response();
    }
}
