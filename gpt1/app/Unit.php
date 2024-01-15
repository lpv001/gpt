<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //
    protected $table = 'units';
    protected $primaryKey = 'id';

    public function unit(){
        return $this->hasMany('App\Product');
    }
}
