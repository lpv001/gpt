<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class Brand
 * @package App\Admin\Models
 * @version August 9, 2019, 4:25 pm UTC
 *
 * @property integer parent_id
 * @property integer lft
 * @property integer rgt
 * @property integer depth
 * @property string default_name
 * @property string slug
 * @property integer order
 * @property string image_name
 * @property boolean is_active
 */
class Brand extends Model
{

    public $table = 'brands';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'name',
        'slug',
        'order',
        'image_name',
        'is_active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'image_name' => 'string',
        'order' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        // 'slug' => 'required',
        // 'order' => 'required',
        'image_name' => 'nullable|image|mimes:jpg,jpeg,png|max:5000',
        'is_active' => 'required'
    ];

    protected $appends = ['name_en', 'name_km'];

    public function getNameEnAttribute()
    {
        $translate_en = BrandTranslation::where([
            'brand_id' => $this->attributes['id'],
            'locale' => 'en'
        ])->first();

        return $translate_en ? $translate_en->name : '';
    }

    public function getNameKmAttribute()
    {
        $translate_en = BrandTranslation::where([
            'brand_id' => $this->attributes['id'],
            'locale' => 'km'
        ])->first();

        return $translate_en ? $translate_en->name : '';
    }
}
