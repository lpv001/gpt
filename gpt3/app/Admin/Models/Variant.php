<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    public $table = 'variants';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'product_id',
        'name',
        'price',
        'sku',
        'stock',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'name' => 'string',
        'price' => 'float',
        'sku' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        'name' => 'required',
        'price' => 'required',
        // 'sku' => 'required',
        // 'stock' => 'required'
    ];

    /**
     * Get all of the comments for the Variant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variant_option_value()
    {
        return $this->hasMany(VariantOptionValue::class, 'variant_id', 'id');
    }
}
