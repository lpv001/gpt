<?php

namespace App\Admin\Models;

use Eloquent as Model;
use App\Admin\Models\Category;

/**
 * Class Product
 * @package App\Admin\Models
 * @version August 5, 2019, 10:48 pm UTC
 *
 * @property string name
 * @property string product_code
 * @property float unit_price
 * @property float sale_price
 * @property string description
 * @property integer unit_id
 * @property integer category_id
 * @property integer is_active
 * @property integer is_promoted
 */
class Product extends Model
{

    public $table = 'products';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'name',
        'product_code',
        'unit_price',
        'sale_price',
        'point_rate',
        'description',
        'unit_id',
        'brand_id',
        'category_id',
        'flag',
        'is_active',
        'is_promoted',
        'user_id',
        'shop_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_code'  => 'string',
        'brand_id' => 'integer',
        'name' => 'string',
        'unit_price' => 'float',
        'sale_price' => 'float',
        'point_rate' => 'integer',
        'description' => 'string',
        'unit_id' => 'integer',
        //'category_id' => 'integer',
        'flag' => 'integer',
        'is_active' => 'integer',
        'is_promoted' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        // 'unit_price' => 'required|numeric',
        // 'sale_price' => 'required|numeric',
        // 'description' => 'required',
        // 'unit' => 'required|numeric',
        'category_ids' => 'required',
        // 'is_active' => 'required',
        // 'is_promoted' => 'required'
    ];

    protected $appends = ['name_en', 'name_km'];

    /**
     *
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasOne('App\Admin\Models\ProductImage');
    }

    public function countTotalProduct()
    {
        $proudcts = Product::all();
        return count($proudcts);
    }

    public function countProductCreatedToday()
    {
        $proudcts = Product::whereRaw('Date(created_at) = CURDATE()')->get();
        return count($proudcts);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function productImage()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function category_translation()
    {
        return $this->hasMany(CategoryTranslation::class, 'category_id', 'category_id');
    }

    public function brand_translation()
    {
        return $this->hasMany(BrandTranslation::class, 'brand_id', 'brand_id');
    }

    public function unit_translation()
    {
        return $this->hasMany(UnitTranslation::class, 'unit_id', 'unit_id');
    }

    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class, 'product_id', 'id');
    }

    

    public function getNameEnAttribute()
    {
        $translate_en = ProductTranslation::where([
            'product_id' => $this->attributes['id'],
            'locale' => 'en'
        ])->first();

        return $translate_en ? $translate_en->name : '';
    }

    public function getNameKmAttribute()
    {
        $translate_en = ProductTranslation::where([
            'product_id' => $this->attributes['id'],
            'locale' => 'km'
        ])->first();

        return $translate_en ? $translate_en->name : '';
    }
}
