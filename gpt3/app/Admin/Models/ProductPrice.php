<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class ProductPrice
 * @package App\Admin\Models
 * @version January 2, 2020, 2:54 am UTC
 *
 * @property integer product_id
 * @property integer type_id
 * @property integer unit_id
 * @property integer city_id
 * @property number unit_price
 * @property number sale_price
 * @property boolean is_active
 */
class ProductPrice extends Model
{

    public $table = 'product_prices';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'id',
        'product_id',
        'type_id',
        'unit_id',
        'city_id',
        'qty_per_unit',
        'unit_price',
        'sale_price',
        'wholesaler',
        'distributor',
        'retailer',
        'buyer',
        'flag',
        'is_active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'type_id' => 'integer',
        'unit_id' => 'integer',
        'city_id' => 'integer',
        'unit_price' => 'float',
        'sale_price' => 'float',
        'wholesaler' => 'float',
        'retailer' => 'float',
        'buyer' => 'float',
        'flag' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        // 'type_id' => 'required',
        'unit_id' => 'required',
        'city_id' => 'required',
        // 'unit_price' => 'required',
        // 'sale_price' => 'required',
        // 'is_active' => 'required'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class , 'city_id');
    }

    
}
