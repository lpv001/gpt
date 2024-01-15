<?php

namespace App;

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


    protected $appends = ['option_value_name'];

    public function getOptionValueNameAttribute()
    {
        $option_value = OptionValue::where('id', $this->attributes['option_value_id'])->first();
        return $option_value ? ucfirst($option_value->name) : '';
    }

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

    /**
     * Get the user associated with the VariantOptionValue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function variant()
    {
        return $this->hasOne(Variant::class, 'id', 'variant_id');
    }

    /**
     * Get all of the comments for the VariantOptionValue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function product()
    // {
    //     return $this->hasMany(Product::class, 'id', 'product_id');
    // }
}
