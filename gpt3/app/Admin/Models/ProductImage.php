<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'option_value_id', 'image_name', 'is_cropped'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('Product');
    }
}
