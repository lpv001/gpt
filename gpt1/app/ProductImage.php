<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_id',
        'option_value_id',
        'image_name',
        'order'
    ];
    public $timestamps = false;
    public function getIdentity()
    {
        return $this->id;
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    /*
    public function getImageNameAttribute()
    {
        return env('PUB_URL') . '/uploads/images/products/thumbnail/small_' . $this->attributes['image_name'];
    }
    */
}
