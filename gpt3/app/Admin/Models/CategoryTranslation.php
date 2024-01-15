<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    //
    protected $table = 'category_translations';
    protected $fillable = ['category_id', 'name', 'locale'];

    public $timestamps = false;
}
