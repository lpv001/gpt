<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class VariantOptionValue extends Model
{
    public $table = 'variant_option_values';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'variant_id',
        'product_id',
        'option_value_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'variant_id' => 'integer',
        'product_id' => 'integer',
        'option_value_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'variant_id' => 'required',
        'product_id' => 'required',
        'option_value_id' => 'required',
    ];

    /**
     * Get all of the comments for the VariantOptionValue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function option_value()
    {
        return $this->hasOne(OptionValue::class, 'id', 'option_value_id');
    }
}
