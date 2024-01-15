<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    //
    protected $table = 'product_translations';
    protected $fillable = ['product_id', 'name', 'description', 'locale'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
