<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateProductRequest;
use App\Admin\Http\Requests\UpdateProductRequest;
use App\Admin\Models\Brand;
use App\Admin\Models\ProductPrice;
use App\Admin\Repositories\ProductPriceRepository;
use App\Admin\Repositories\ProductRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\Category;
use App\Admin\Models\CategoryTranslation;
use App\Admin\Models\Unit;
use App\Admin\Models\City;
use App\Admin\Models\Option;
use App\Admin\Models\OptionValue;
use App\Admin\Models\ProductImage;
use App\Admin\Models\Product;
use App\Admin\Models\ProductOption;
use App\Frontend\Models\CityProvince;
use App\Admin\Models\ProductTranslation;
use App\Admin\Models\Promotion;
use App\Admin\Models\Shop;
use App\Admin\Models\Variant;
use App\Admin\Models\VariantOptionValue;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductController extends AppBaseController
{
    /** @var  ProductRepository */
    private $productRepository;

    private $productPriceRepository;
    private $path;
    private $shops;
    private $categories;
    private $brands;
    private $units;
    private $cities;


    public function __construct(ProductRepository $productRepo, ProductPriceRepository $productPriceRepo)
    {
        $this->productRepository = $productRepo;
        $this->productPriceRepository = $productPriceRepo;
        $this->path = public_path('/uploads/images/products');
        $this->categories = Category::getName();
        $this->units = DB::table('unit_translations')->where('locale', \App::getLocale())->pluck('name', 'unit_id')->toArray();
        $this->brands = DB::table('brand_translations')->where('locale', \App::getLocale())->pluck('name', 'brand_id')->toArray();
        $this->cities = DB::table('city_province_translations')->where('locale', \App::getLocale())->pluck('name', 'city_province_id')->toArray();
        $this->shops = Shop::pluck('name', 'id')->toArray();
    }

    /**
     * 
     * 
     */
    public function generateThumbnails()
    {
        $images = ProductImage::where('updateflag', 0)->skip(0)->take(400)->get();
        if (count($images) < 1) {
            dd("DONE");
        }
        
        foreach ($images as $key => $value) {
            $image_path = $this->path . '/' . $value->image_name;
            $new_name = $value->product_id . '_' . $value->image_name;
            if (file_exists($image_path)) {
                rename($this->path . '/' . $value->image_name, $this->path . '/' . $new_name);
                //createThumbnail($this->path, $new_name);
            }
            ProductImage::where('id', $value->id)->update(['updateflag' => 1, 'image_name' => $new_name]);
        }
        
        return true;
    }

    /**
     * 
     * 
     */
    public function updateImageWork()
    {
        $images = ProductImage::where('updateflag', 0)->skip(0)->take(1000)->get();
        if (count($images) < 1) {
            dd("DONE");
        }
        
        foreach ($images as $key => $value) {
          $parts = explode('_', $value->image_name);
          $filename = $parts[2];
          $image_path = $this->path . '/' . $filename;
          $new_name = $value->product_id . '_' . $filename;
          if (file_exists($image_path)) {
              rename($this->path . '/' . $filename, $this->path . '/' . $new_name); // org image
              rename($this->path . '/thumbnail/small_' . $filename, $this->path . '/thumbnail/small_' . $new_name); // small image
              rename($this->path . '/thumbnail/medium_' . $filename, $this->path . '/thumbnail/medium_' . $new_name); // medium image
              rename($this->path . '/thumbnail/large_' . $filename, $this->path . '/thumbnail/large_' . $new_name); // large image
              rename($this->path . '/icon/icon_' . $filename, $this->path . '/icon/icon_' . $new_name); // icon image
              ProductImage::where('id', $value->id)->update(['updateflag' => 1, 'image_name' => $new_name]);
          }
        }
        
        return true;
    }

    /**
     * This is just tool
     * @return Response
     */
    public function updateProductImageIDbyOptionValue()
    {
      $images = ProductImage::where('product_id', 0)->take(1000)->get();
      
      if ($images) {
        foreach ($images as $img) {
          $option = DB::table('option_values as ov')
                 ->join('product_options as po', 'ov.option_id', '=', 'po.option_id')
                 ->select(['ov.id', 'po.product_id', 'ov.option_id'])
                 ->where('ov.id', $img->option_value_id)
                 ->first();
          
          if ($option) {
            ProductImage::where('id', $img->id)->update(['product_id' => $option->product_id]);
          }
        }
      }
    }

    /**
     * This is just tool
     * @return Response
     */
    public function checkDuplicateImages()
    {
      $images = ProductImage::where('updateflag', 0)->take(1000)->get();
      
      if ($images) {
        foreach ($images as $img) {
          $dp_img = ProductImage::where('image_name', $img->image_name)->orderBy('id', 'DESC')->get();
          $c = count($dp_img);
          $i = 1;
          if ($c > 1) {
            foreach ($dp_img as $di) {
              if ($i < $c) {
                $i++;
                ProductImage::where('id', $di->id)->update(['updateflag' => 2]);
              }
            }
          } else {
            ProductImage::where('id', $img->id)->update(['updateflag' => 1]);
          }
        }
      }
      
    }

    /**
     * Display a listing of the Product.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //$this->generateThumbnails();dd("DONE");
        
        //========= Count product
        $product = new Product;
        $countTotalProduct = $product->countTotalProduct();
        $countProductCreatedToday = $product->countProductCreatedToday();
        
        $categories = Category::getName();
        $brands = DB::table('brand_translations')->where('locale', \App::getLocale())->pluck('name', 'brand_id')->toArray();
        $shops = DB::table('shops')->pluck('name', 'id')->toArray();
        
        $products = Product::with([
          'images' => function ($q) {
              return $q->select('product_id', 'image_name');
          },
          'product_translations'])

          ->where(function ($query) use ($request) {
              if ($request->code) {
                $query->where('product_code', 'LIKE', '%' . $request->code . '%');
              }
              
              if ($request->name) {
                $query->whereHas('product_translations', function (Builder $q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->name . '%');
                });
              }
              
              if ($request->category) {
                $query->where('category_id', $request->category);
              }
              
              if ($request->brand) {
                $query->where('brand_id', $request->brand);
              }
              
              if ($request->shop) {
                $query->where('shop_id', $request->shop);
              }
              
              if ($request->product_option) {
                if ($request->product_option == 1) {
                  $poimgs = DB::table('product_options')->select('product_id')
                            ->orderBy('product_id', 'DESC')->groupBy('product_id')->get();
                  $plist = [];
                  foreach ($poimgs as $poi) {
                    $plist[] = intval($poi->product_id);
                  }
                  $query->whereIn('id', $plist);
                } elseif ($request->product_option == 2) {
                  $poimgs = ProductImage::select('product_id')->where('option_value_id', '>', 0)->where('product_id', '>', 0)
                            ->orderBy('product_id', 'DESC')->groupBy('product_id')->get();
                  $plist = [];
                  foreach ($poimgs as $poi) {
                    $plist[] = intval($poi->product_id);
                  }
                  $query->whereIn('id', $plist);
                }
              }
          })
          ->orderBy('id', 'DESC')
          
          ->select(['id', 'product_code', 'name', 'brand_id', 'category_id', 'shop_id', 'is_active'])
          ->paginate(10);
          
        return view('admin::products.index', compact('products', 'countTotalProduct', 'countProductCreatedToday', 'categories', 'brands', 'shops'));
    }

    /**
     *
     */
    public function datatablesOrg(Request $request)
    {
      //$limit = $request->get("length");
      //$limit = $request->input('length');
      $limit = 100;
      $start = $request->input('start');
        
        $products = Product::with([
            'images' => function ($q) {
                return $q->select('product_id', 'image_name');
            },
            'product_translations'])
            
            ->where(function ($query) use ($request) {
                if ($request->code)
                    $query->where('product_code', 'LIKE', '%' . $request->code . '%');

                if ($request->name) {
                    $query->whereHas('product_translations', function (Builder $q) use ($request) {
                        $q->where('name', 'LIKE', '%' . $request->name . '%');
                    });
                }

                if ($request->category)
                    $query->where('category_id', $request->category);

                if ($request->brand)
                    $query->where('brand_id', $request->brand);
            })
            ->orderBy('id', 'DESC')
            
            ->skip(3)
            ->take($limit)
            
            ->get(['id', 'product_code', 'name', 'brand_id', 'category_id', 'is_active']);

        return DataTables::of($products)
            ->addIndexColumn()
            ->editColumn('category', function ($product) {
                /// Added by BTY
                $category_names = [];
                foreach ($product->categories as $cat) {
                    $category_names[] = $cat->default_name;
                }
                $catname_str = implode("; ", $category_names);
                return $catname_str;
                /// EOF Added by BTY
                //$category_trans = DB::table('category_translations')->where(['category_id' => $product->category_id, 'locale' => \App::getLocale()])->first();
                //return $category_trans ? $category_trans->name : '';
            })
            ->editColumn('brand', function ($product) {
                $brand_trans = DB::table('brand_translations')->where(['brand_id' => $product->brand_id, 'locale' => \App::getLocale()])->first();
                return $brand_trans ? $brand_trans->name : '';
            })
            ->editColumn('images', function ($product) {
                return $product->images ? '<img src="' . asset('/uploads/images/products/thumbnail/small_' . $product->images->image_name) . '" alt="" srcset="" height="70">' : '';
            })
            ->editColumn('is_active', function ($product) {
                return $product->is_active == 1 ? '<button type="button" data-id="' . $product->id . '" data-type="active" class="btn btn-xs btn-success product-status">Enable</button>' :
                    '<button type="button" data-id="' . $product->id . '" data-type="in-active" class="btn btn-xs btn-danger product-status">Disable</button>';
            })
            ->addColumn('action', function ($product) {
                $action = '';
                $action .= '<a href="' . route('products.show', $product->id) . '" class="btn btn-warning btn-xs mx-1"><i class="glyphicon glyphicon-eye-open"></i></a>';
                $action .= '<a href="' . route('products.edit', $product->id) . '" class="btn btn-primary btn-xs mx-1"><i class="glyphicon glyphicon-edit"></i></a>';
                $action .= '<a class="btn btn-danger btn-xs mx-1 btn-delete" data-id="' . $product->id . '"><i class="glyphicon glyphicon-trash"></i></a>';
                return $action;
            })
            ->rawColumns(['action', 'images', 'is_active'])
            ->toJson();
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {
        $categories = $this->categories;
        $units = $this->units;
        $brands = $this->brands;
        $cities = $this->cities;
        $shops = $this->shops;
        $is_cropped_image = 1;

        return view('admin::products.create', compact('categories', 'units', 'brands', 'cities', 'shops', 'is_cropped_image'));
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        try {
            $input = [
                'user_id'   => 1,
                'shop_id'   => $request->shop_id ?? 1,
                'product_code'  =>  $request->product_code,
                'name'  =>  $request->name[0],
                'unit_price'  =>  $request->price,
                'sale_price'  =>  $request->price,
                'point_rate'  =>  0,
                'description'  =>  $request->description[0] ?? '',
                'unit_id'  =>  $request->unit[0],
                'category_id'  =>  $request->category_ids[0],
                'brand_id'  =>  $request->brand_id,
                'flag'  =>  0,
                'is_active'  =>  isset($request->is_active) ? 1 : 0,
                'is_promoted'  =>  isset($request->is_promoted) ? 1 : 0,
            ];
            $product = $this->productRepository->create($input);

            $this->productOption($request, $product);

            // name tranlsate 
            $_name[0] = ['local' => 'en', 'name' => ''];
            $_name[1] = ['local' => 'km', 'name' => ''];
            // description tranlsate 
            $description[0] = ['locale' =>  'en', 'name' => '', 'description' => ''];
            $description[1] = ['locale' =>  'km', 'name' => '', 'description' => ''];
            foreach ($request->lang as $item_key => $item) {
                if ($item === 'en') {
                    $_name[0]['name'] = $request->name[$item_key];
                    $description[0]['description'] = $request->description[$item_key] ?? '';
                }

                if ($item === 'km') {
                    $_name[1]['name'] = $request->name[$item_key];
                    $description[1]['description'] = $request->description[$item_key] ?? '';
                }
            }

            $lang = ['en', 'km'];
            foreach ($lang as $key => $value) {
                ProductTranslation::create([
                    'product_id'    =>  $product->id,
                    'name'          =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
                    'description'   =>  $description[$key]['description'] === '' ? $description[0]['description'] : $description[$key]['description'],
                    'locale'        =>  $value,
                ]);
            }

            // Saving images
            if ($request->hasFile('images')) {
              foreach ($request->images as $image) {
                $is_cropped = 1;
                $filename = $product->id . '_' . uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $image->move($this->path, $filename);
                if ($request->input('enableCropImage')) {
                  createThumbnail($this->path, $filename);
                } else {
                  $is_cropped = 0;
                  createThumbnailNoneCrop($this->path, $filename);
                }
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_name' => $filename,
                    'is_cropped' => $is_cropped
                ]);
              }
            }

            // Save category product
            $category = Category::find($request->category_ids);
            $product->categories()->attach($category);

            Flash::success('Product saved successfully.');

            return redirect(route('products.index'));
        } catch (Exception $e) {
            return \Redirect::back()->withInput($request->all());
        }
    }

    /**
     * Display the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $product = Product::with([
            'category' => function ($q) {
                return $q->select('id', 'default_name');
            },
            'brand' =>  function ($q) {
                return $q->select('id', 'name');
            },
            'images' => function ($q) {
                return $q->select('product_id', 'image_name');
            }
        ])->find($id);

        $product_price = ProductPrice::where('product_id', $id)->get();

        return view('admin::products.show', compact('product', 'product_price'));
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
      //$this->checkDuplicateImages();
      //$this->updateProductImageIDbyOptionValue();
      //dd("DONE");
      
      
        $product = $this->productRepository->find($id);
        $cids = array();
        if (isset($product->categories)) {
          foreach ($product->categories as $cat) {
              $cids[] = $cat->id;
          }
        }
        if (count($cids) > 0) {
            $product->category_ids = $cids;
        }
        
        $categories = $this->categories;
        $units = $this->units;
        $brands = $this->brands;
        $cities = $this->cities;
        $shops = $this->shops;
        $productPrices = ProductPrice::where('product_id', $id)->get();
        $image = ProductImage::where('product_id', $id)->whereNull('option_value_id')->pluck('image_name', 'id');
        
        // Check if cropped image
        $is_cropped_image = 0;
        $cropped_image = ProductImage::where('product_id', $id)->where('is_cropped', 1)->first();
        if ($cropped_image) {
          $is_cropped_image = 1;
        }
        
        $edit = 1;

        $product_options =  ProductOption::where('product_id', $id)
            ->with([
                'option.option_value' => function ($query) {
                    return $query->select('name', 'option_id', 'id');
                },
                'option.option_value.image' => function ($query) {
                    return $query->select('option_value_id', 'image_name', 'id');
                }
            ])->get(['id', 'option_id']);

        $variants = Variant::where('product_id', $id)
            ->with([
                'variant_option_value' => function ($query) {
                    return $query->select('variant_id', 'option_value_id');
                },
                'variant_option_value.option_value' => function ($query) {
                    return $query->select('id', 'name');
                },
            ])->get();

        $product_translation = ProductTranslation::where('product_id', $id)->get();

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('products.index'));
        }

        return view('admin::products.edit', compact(
            'product',
            'categories',
            'units',
            'cities',
            'brands',
            'edit',
            'productPrices',
            'is_cropped_image',
            'image',
            'product_translation',
            'product_options',
            'variants',
            'shops'
        ));
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            $product = $this->productRepository->find($id);
            
            if (empty($product)) {
                Flash::error('Product not found');
                return redirect(route('products.index'));
            }
            
            // Updating category product
            if ($request->category_ids[0]) {
              $product->categories()->sync($request->input('category_ids', []));
            }
            
            $input = [
                'user_id'   => 1,
                'shop_id'   => $request->shop_id ?? 1,
                'product_code'  =>  $request->product_code,
                'name'  =>  $request->name[0],
                'unit_price'  =>  $request->price,
                // 'sale_price'  =>  $request->price,
                'point_rate'  =>  0,
                'description'  =>  $request->description[0],
                'unit_id'  =>  $request->unit ?? 0,
                //'category_id'  =>  $request->category_id,
                'brand_id'  =>  $request->brand_id ?? 0,
                'flag'  =>  0,
                'is_active'  =>  isset($request->is_active) ? 1 : 0,
                //'is_promoted'  =>  isset($request->is_active) ? 1 : 0,
            ];

            $product = $this->productRepository->update($input, $id);

            $this->productOption($request, $product);

            // name tranlsate 
            $_name[0] = ['local' => 'en', 'name' => ''];
            $_name[1] = ['local' => 'km', 'name' => ''];
            // description tranlsate 
            $description[0] = ['locale' =>  'en', 'name' => '', 'description' => ''];
            $description[1] = ['locale' =>  'km', 'name' => '', 'description' => ''];
            foreach ($request->lang as $item_key => $item) {
                if ($item === 'en') {
                    $_name[0]['name'] = $request->name[$item_key];
                    $description[0]['description'] = $request->description[$item_key] ?? '';
                }

                if ($item === 'km') {
                    $_name[1]['name'] = $request->name[$item_key];
                    $description[1]['description'] = $request->description[$item_key] ?? '';
                }
            }

            $lang = ['en', 'km'];
            foreach ($lang as $key => $value) {
                ProductTranslation::updateOrCreate([
                    'product_id'  =>  $id,
                    'locale'    =>  $value
                ], [
                    'name'      =>  $_name[$key]['name'] === '' ? $_name[0]['name'] : $_name[$key]['name'],
                    'description'   =>  $description[$key]['description'] === '' ? $description[0]['description'] : $description[$key]['description'],
                ]);
            }
            
            $image = isset($request->old) ? ProductImage::where('product_id', $id)->whereNull('option_value_id')->whereNotIn('id', $request->old) :
                                            ProductImage::where('product_id', $id)->whereNull('option_value_id');
            foreach ($image->get() as $key => $value) {
                $image_path = $this->path . '/' . $value->image_name;
                if (file_exists($image_path)) {
                    unlink($image_path);
                    deleteThumbnail($this->path, $value->image_name);
                }
            }
            $image->delete();
            
            // Replace new product images
            if (isset($request->images)) {
              foreach ($request->images as $image) {
                $is_cropped = 1;
                $filename = $product->id . '_' . uniqid() . time() . '.' . $image->getClientOriginalExtension();
                $image->move($this->path, $filename);
                if ($request->input('enableCropImage')) {
                  createThumbnail($this->path, $filename);
                } else {
                  $is_cropped = 0;
                  createThumbnailNoneCrop($this->path, $filename);
                }
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_name' => $filename,
                    'is_cropped' => $is_cropped
                ]);
              }
            }
            
            Flash::success('Product updated successfully.');
            return redirect(route('products.index'));
        } catch (\Exception $e) {
            return $e->getMessage();
            return \Redirect::back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $product = $this->productRepository->find($id);
        
        if ($product) {
            // Delete category product
            $product->categories()->detach();
            
            $images = ProductImage::where('product_id', $id)->get();
            if ($images) {
                foreach ($images as $key => $value) {
                    $image_path = public_path($this->path . '/' . $value->image_name);
                    if (file_exists($image_path)) {
                        unlink($image_path);
                        deleteThumbnail($this->path, $value->image_name);
                    }
                }
            }
            ProductImage::where('product_id', $id)->delete();
            ProductTranslation::where('product_id', $id)->delete();
            ProductPrice::where('product_id', $id)->delete();
            $this->productRepository->delete($id);
            return response()->json(['status'   =>  200,    'message'   =>  'Product Deleted Successfully!']);
        }

        return response()->json(['status'   =>  400,    'message'   =>  'Product not found!']);
    }

    /**
     *
     */
    public function removeById(Request $request)
    {
        if (isset($request->_ids)) {
            foreach ($request->_ids as $value) {
                $product = $this->productRepository->find($value);

                if ($product) {
                    $images = ProductImage::where('product_id', $value)->get();
                    if ($images) {
                        foreach ($images as $key => $value) {
                            $image_path = public_path($this->path . '/' . $value->image_name);
                            if (file_exists($image_path)) {
                                unlink($image_path);
                                deleteThumbnail($this->path, $value->image_name);
                            }
                        }
                    }
                    ProductImage::where('product_id', $value)->delete();
                    ProductTranslation::where('product_id', $value)->delete();
                    ProductPrice::where('product_id', $value)->delete();

                    $product->delete($value);
                }
            }
            return response()->json(['status'   =>  200,    'message'   =>  'Product Deleted Successfully!']);
        }

        return response()->json(['status'   =>  400,    'message'   =>  'Product not found!']);
    }

    /**
     *
     */
    public function getSubUnits($id)
    {
        $data = Unit::where('parent_id', $id)->pluck('name', 'id');

        return response()->json($data);
    }

    /**
     *
     */
    public function changeProductStatus(Request $request)
    {
        if ($request->ajax()) {
            Product::where('id', $request->id)->update(['is_active' => $request->status]);

            return response()->json(['status' => 200, 'message' => 'Product updated successfully.']);
        }
    }

    /**
     *
     */
    public function getTotalProductBy(Request $request)
    {
        $new_product = Product::whereDate('created_at', Carbon::today())
            ->where(function ($query) use ($request) {
                if ($request->code)
                    $query->where('product_code', 'LIKE', '%' . $request->code . '%');

                if ($request->name)
                    $query->where('name', 'LIKE', '%' . $request->name . '%');

                if ($request->category)
                    $query->where('category_id', $request->category);

                if ($request->brand)
                    $query->where('category_id', $request->brand);
            })->count();

        $total_product = Product::where(function ($query) use ($request) {
            if ($request->code)
                $query->where('product_code', 'LIKE', '%' . $request->code . '%');

            if ($request->name)
                $query->where('name', 'LIKE', '%' . $request->name . '%');

            if ($request->category)
                $query->where('category_id', $request->category);

            if ($request->brand)
                $query->where('category_id', $request->brand);
        })->count();


        $data = ['new_product' => $new_product, 'total_product' => $total_product];
        return response()->json(['status' => 200, 'data' => $data]);
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
            if (isset($request->options) && ($request->options[0] !=null)) {
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
          //dd("fatal error", $e);
          return $e->getMessage();
        }
    }
}
