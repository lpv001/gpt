<?php

namespace App\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

class CityProvince extends Model
{
    protected $table = 'city_provinces';


    public function city_tranlsate()
    {
        return $this->hasMany(CityProvinceTranslate::class);
    }
}
