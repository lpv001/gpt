<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class District
 * @package App\Admin\Models
 * @version December 7, 2019, 12:41 pm UTC
 *
 * @property \App\Admin\Models\CityProvince cityProvince
 * @property \Illuminate\Database\Eloquent\Collection districtTranslations
 * @property string iso_code
 * @property integer city_province_id
 * @property string default_name
 * @property string slug
 * @property number lat
 * @property number lng
 * @property integer order
 * @property boolean is_active
 */
class District extends Model
{

    public $table = 'districts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'iso_code',
        'city_province_id',
        'default_name',
        'slug',
        'lat',
        'lng',
        'order',
        'is_active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'iso_code' => 'string',
        'city_province_id' => 'integer',
        'default_name' => 'string',
        'slug' => 'string',
        'lat' => 'float',
        'lng' => 'float',
        'order' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'city_province_id' => 'required',
        'order' => 'required',
        'is_active' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cityProvince()
    {
        return $this->belongsTo(\App\Admin\Models\CityProvince::class, 'city_province_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function districtTranslations()
    {
        return $this->hasMany(\App\Admin\Models\DistrictTranslation::class, 'district_id');
    }
}
