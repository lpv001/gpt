<?php

namespace App\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';

    static function getDistrictByCity($cityId, $locale)
    {
        $districts = \DB::table('districts')
            ->join('district_translations', 'districts.id', 'district_translations.district_id')
            ->where('districts.city_province_id', $cityId)
            ->where('district_translations.locale', $locale)
            ->pluck('district_translations.name', 'districts.id');

        return $districts;
    }
}
