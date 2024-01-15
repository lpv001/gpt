<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App;

class Districts extends Model
{
    protected $table = 'districts';
    protected $primaryKey = 'id';
	protected $fillable = [
		'iso_code',
        'city_province_id',
        'default_name',
        'slug',
        'lng',
        'lon',
    ];

    public function getIdentity(){
        return $this->id;
    }

    public function Shop(){
        return $this->hasMany('App\Shop');
    }

    /**
     *
     */
    public function getDistrictName($district_id){
        $locale = App::getLocale();
        
        return DB::table('district_translations')
        ->where([
                ['district_id', $district_id],
                ['locale', $locale]
                ])
        ->select('name as default_name')
        ->first();
    }

    /**
     *
     */
    public function getDistrictList($province_id){
        $locale = App::getLocale();
        
        return DB::table('districts as d')
        ->join('district_translations as dt', 'd.id', '=', 'dt.district_id')
        ->where([
                ['d.city_province_id', $province_id],
                ['dt.locale', $locale]
                ])
        ->select('d.id','dt.name as default_name')
        ->orderBy('dt.name', 'ASC')
        ->get();
    } // EOF
}
