<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class BrandTranslation extends Model
{
    //
    protected $table = 'brand_translations';
    protected $fillable = ['brand_id', 'name', 'locale'];

    public $timestamps = false;
}
