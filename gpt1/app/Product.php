<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App;
use App\BrandTranslations;
use App\Category;

class Product extends Model
{
    /*
        Status Product
        0 => inactive
        1 => rejected
        2 => approved
    */

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'unit_price',
        'sale_price',
        'description',
        'unit_id',
        'category_id',
        'brand_id',
        'user_id',
        'shop_id',
        'is_active',
        'flag',
        'product_code',
        'status',
    ];

    protected $appends = ['brand_name', 'category_name', 'unit_name'];

    /**
     *
     */
    private function getImageURL()
    {
        return env('PUB_URL') . '/uploads/images/products';
    }

    /**
     *
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     *
     */
    public function brands()
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    /**
     *
     */
    public function unit()
    {
        return $this->belongsTo('App\Unit', 'unit_id');
    }

    /**
     *
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    /**
     * Get the image associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id');
    }

    /**
     *
     */
    public function prices()
    {
        return $this->hasMany('App\ProductPrice')->orderBy('unit_price', 'ASC');
    }

    public function names()
    {
        return $this->hasMany(ProductTranslation::class, 'product_id', 'id');
    }

    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variant_option()
    {
        return $this->hasMany(VariantOptionValue::class, 'product_id', 'id');
    }

    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(Variant::class, 'product_id', 'id');
    }

    /**
     * Get master product list
     * BTY
     */
    public function getMasterProductList()
    {
        return DB::table('products as p')
            ->select('p.*')
            ->where('p.is_active', 1)
            ->get();
    }

    /**
     * Get master product list
     * BTY
     */
    public function getProductSettings($key = null)
    {
        return DB::table('settings')
            ->select('*')
            ->where([
                ['setting_module', 'product'],
                ['setting_key', $key]
            ])
            ->first();
    }

    /**
     * Get Product list : BTY for multi-price
     */
    public function getProductList($params = null, $limit = 12, $page = 1)
    {
        $locale = App::getLocale();

        if (!isset($params['load'])) {
            $params['load'] = 'order_products';
        }

        $query = DB::table('products as p')
            ->join('shops as s', 's.id', '=', 'p.shop_id')
            ->leftJoin('product_translations as pt', 'pt.product_id', '=', 'p.id')
            ->leftJoin('category_translations as c', 'c.category_id', '=', 'p.category_id')
            ->leftJoin('brand_translations as b', 'b.brand_id', '=', 'p.brand_id')
            ->leftJoin('units as un', 'un.id', 'p.unit_id')
            ->leftJoin('product_images as pi', 'p.id', '=', 'pi.product_id')
            ->where(function ($q) use ($params) {
                if (isset($params['product_id'])) {
                    $q->where('p.id', $params['product_id']);
                }

                if (isset($params['shop_id'])) {
                    if ($params['shop_id'] != null) {
                        $q->where('p.shop_id', $params['shop_id']);
                    }
                }

                if (isset($params['shop_list'])) {
                    if ($params['shop_list'] != null) {
                        $q->whereIn('shop_id', $params['shop_list']);
                    }
                }

                if (isset($params['category_id']) && $params['category_id'] != null) {
                    $q->where('p.category_id', $params['category_id']);
                }

                if (isset($params['brand_id'])) {
                    if ($params['brand_id'] != null) {
                        $q->where('p.brand_id', $params['brand_id']);
                    }
                }

                if (isset($params['search_by_name'])) {
                    $q->where('pt.name', 'LIKE', '%' . $params['search_by_name'] . '%');
                }

                if (isset($params['load'])) {
                    if ($params['load'] != 'random_products') {
                        $q->orderBy('p.id', 'DESC');
                    }
                }
            })
            ->select(
                'p.id',
                'pt.name',
                'p.point_rate',
                'pt.description',
                'p.is_promoted',
                'p.flag',
                'p.sale_price',
                'p.unit_price',
                'p.category_id',
                'p.brand_id',
                'c.name as category_name',
                'b.name as brand_name',
                'un.name as unit_name',
                's.name as shop_name',
                'p.unit_id',
                'p.shop_id',
                'p.is_active',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", pi.image_name) AS image_name')
            )
            ->where([
                ['b.locale', $locale],
                ['c.locale', $locale],
                ['pt.locale', $locale],
                ['p.is_active', 1]
            ])
            ->groupBy('p.id')
            ->skip(($page - 1) * $limit)->take($limit);

        return $query->get();
    } // EOF

    /**
     *
     */
    public function getDetailofProduct($product_id, $params = null)
    {
        $query = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->join('brands as b', 'p.brand_id', '=', 'b.id')
            ->join('units as un', 'un.id', 'p.unit_id')
            ->leftJoin('product_images as pi', 'p.id', '=', 'pi.product_id')
            ->select(
                'p.id',
                'p.name',
                'p.point_rate',
                'p.description',
                'p.is_promoted',
                'p.flag',
                'p.sale_price',
                'p.unit_id',
                'p.unit_price',
                'c.default_name as category_name',
                'un.name as unit_name',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", pi.image_name) AS image_name')
            )
            ->where('p.id', $product_id);
        return $query->first();
    } // EOF

    /**
     * Search product by name
     */
    public function getProductByName($name)
    {
        return DB::table('products')
            ->leftJoin('product_images as pi', 'products.id', '=', 'pi.product_id')
            ->join('units as u', 'products.unit_id', '=', 'u.id')
            ->join('categories as c', 'products.category_id', '=', 'c.id')
            ->select(
                'products.*',
                'u.name as unit_name',
                'c.default_name as category_name',
                'p.unit_id',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", pi.image_name) AS image_name')
            )
            ->where('products.is_active', 1)
            ->where('products.name', 'like', '%' . $name . '%')
            ->get();
    }

    /**
     * Get products relation category
     */
    public function getProductsByCategory($category_id)
    {
        return Product::with('categories')
            ->leftJoin('product_images as pi', 'products.id', '=', 'pi.product_id')
            ->join('units as u', 'products.unit_id', '=', 'u.id')
            ->select(
                'products.*',
                'u.name as unit_name',
                'p.unit_id',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", pi.image_name) AS image_name')
            )
            ->where('category_id', $category_id)
            ->orderBy('name')
            ->get();
    }

    /**
     *
     */
    public function getNameAttribute()
    {
        $product = ProductTranslation::where([
            'product_id' => $this->attributes['id'],
            'locale'  => \App::getLocale(),
        ])->first();

        return $product ? $product->name : '';
    }

    /**
     *
     */
    public function getDescriptionAttribute()
    {
        $product = ProductTranslation::where([
            'product_id' => $this->attributes['id'],
            'locale'  => \App::getLocale(),
        ])->first();

        return $product ? $product->description : '';
    }

    /**
     *
     */
    public function getBrandNameAttribute()
    {
        if (array_key_exists('brand_id', $this->attributes)) {
            $brand = \DB::table('brand_translations')->where(
                [
                    'brand_id' => $this->attributes['brand_id'],
                    'locale'  => \App::getLocale(),
                ]
            )
                ->first();
            return $brand ? $brand->name : '';
        }
        return '';
    }

    public function getUnitNameAttribute()
    {
        if (array_key_exists('unit_id', $this->attributes)) {
            $unit = \DB::table('unit_translations')->where(
                [
                    'unit_id' => $this->attributes['unit_id'],
                    'locale'  => \App::getLocale(),
                ]
            )
                ->first();
            return $unit ? $unit->name : '';
        }
        return '';
    }

    /**
     *
     */
    public function getCategoryNameAttribute()
    {
        if (array_key_exists('category_id', $this->attributes)) {
            $category = \DB::table('category_translations')->where(
                [
                    'category_id' => $this->attributes['category_id'],
                    'locale'  => \App::getLocale(),
                ]
            )
                ->first();
            return $category ? $category->name : '';
        }
        return '';
    }

    /**
     * Just need to return shop_id of given product.
     */
    public function getProductShopId($product_id)
    {
        $query = DB::table('products as p')
            ->select('p.shop_id')
            ->where('p.id', $product_id)
            ->first();
        return $query->shop_id;
    } // EOF


    // New Code
    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product_option()
    {
        return $this->hasMany(ProductOption::class, 'product_id', 'id');
    }

    /**
     * Get all of the translate for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translate()
    {
        return $this->hasMany(ProductTranslation::class, 'product_id', 'id');
    }
}
