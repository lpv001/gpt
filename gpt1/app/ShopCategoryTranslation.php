<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCategoryTranslation extends Model
{
    //
    protected $table = 'shop_category_translations';
    protected $fillable = ['shop_category_id', 'name', 'locale'];

    public $timestamps = false;
}
