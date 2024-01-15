<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    //
    protected $table = 'product_translations';
    protected $fillable = ['product_id', 'name', 'description', 'locale'];

    public $timestamps = false;
}
