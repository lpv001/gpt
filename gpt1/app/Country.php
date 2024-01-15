<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Dimsav\Translatable\Translatable;


class Country extends Model
{
    protected $table = 'country';    
    public $translatedAttributes = ['name'];
    protected $fillable = ['code'];

    protected $hidden = ['created_at', 'updated_at'];

    public function getIdentity(){
        return $this->id;
    }

    public function City_province(){
        return $this->hasMany('App\City_provinces');
    }

    public function getCountryName($country_id){
        return Country::findOrFail($country_id)->select('code')->first();
    }
}
