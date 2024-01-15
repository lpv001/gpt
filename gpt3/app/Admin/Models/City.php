<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class City
 * @package App\Admin\Models
 * @version December 7, 2019, 12:40 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection cityProvinceTranslations
 * @property \Illuminate\Database\Eloquent\Collection districts
 * @property string iso_code
 * @property string default_name
 * @property string slug
 * @property number lat
 * @property number lng
 * @property boolean is_city
 * @property integer order
 * @property boolean is_active
 * @property integer country_id
 */
class City extends Model
{

    public $table = 'city_provinces';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'iso_code',
        'default_name',
        'slug',
        'lat',
        'lng',
        'is_city',
        'order',
        'is_active',
        'country_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'iso_code' => 'string',
        'default_name' => 'string',
        'slug' => 'string',
        'lat' => 'float',
        'lng' => 'float',
        'is_city' => 'boolean',
        'order' => 'integer',
        'is_active' => 'boolean',
        'country_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'is_city' => 'required',
        'order' => 'required',
        'is_active' => 'required',
        'country_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cityProvinceTranslations()
    {
        return $this->hasMany(\App\Admin\Models\CityProvinceTranslation::class, 'city_province_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function districts()
    {
        return $this->hasMany(\App\Admin\Models\District::class, 'city_province_id');
    }
}
