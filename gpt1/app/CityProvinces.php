<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App;

class CityProvinces extends Model
{
    protected $table = 'city_provinces';
    protected $primaryKey = 'id';
	protected $fillable = [
		'iso_code',
        'default_name',
        'country_id',
        'slug',
        'lng',
        'lon'
    ];

    public function getIdentity(){
        return $this->id;
    }

    public function Shops(){
        return $this->hasOne('App\Shop');
    }

    public function Country(){
        return $this->belongTo('App\Country');
    }

    public function Disricts(){
        return $this->hasMany('App\Districts');
    }

    /**
     * 
     */
    public function getCityName($city_id){
        $locale = App::getLocale();
        
        return DB::table('city_province_translations')
            ->where([
                    ['city_province_id', $city_id],
                    ['locale', $locale]
                    ])
            ->select('name as default_name')
            ->first();
    }
    
    /**
     * 
     */
    public function getProvinceList($country_id) {
        $locale = App::getLocale();
        
        return DB::table('city_provinces as c')
            ->join('city_province_translations as ct', 'c.id', '=', 'ct.city_province_id')
            ->where([
                    ['c.country_id', $country_id],
                    ['ct.locale', $locale]
                    ])
            ->select('c.id','ct.name','ct.name as default_name')
            ->orderBy('ct.name', 'ASC')
            ->get();
    } // EOF
}
