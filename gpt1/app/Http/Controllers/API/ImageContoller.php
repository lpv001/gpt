<?php

namespace App\Http\Controllers\API;

use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\ProductImage;
use App\Helper\ImageHandler;

use Respone;
use DB;
use Exception;
use Illuminate\Support\Facades\DB as FacadesDB;
use Validator;

class ImageController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;
    public $path = '/uploads/images/products/';

    /**
     *
     */
    public function modifyProductImages()
    {
        try {
            $images = ProductImage::where('updateflag', 0)->skip(0)->take(1000)->get();
            if (count($images) < 1) {
                dd("DONE");
            }
            
            $image_handler = new ImageHandler;
            foreach ($images as $key => $value) {
              $image_path = public_path($this->path . $value->image_name);
              if (file_exists($image_path)) {
                $image_handler->createThumbnailNoneCrop($this->path, $value->image_name);
                ProductImage::where('id', $value->id)->update(['updateflag' => 1]);
              }
              dd($value);
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
                return response()->json($response, 401);
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
            $product->is_active = isset($request->is_active) ? 1 : 0;
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
                                ProductImage::create([
                                    'product_id' => $product->id,
                                    'option_id' => $option->id,
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
