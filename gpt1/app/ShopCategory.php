<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    //
    protected $table = 'shop_categories';
    protected $fillable = ['order_id', 'image_name', 'is_active'];

    protected $appends = ['name'];

    public function getImageNameAttribute()
    {
        return asset('/uploads/images/shops/categories/' . $this->attributes['image_name']);
    }

    public function getNameAttribute()
    {
        $shop_category = ShopCategoryTranslation::where('shop_category_id', $this->attributes['id'])
            ->where('locale', \App::getLocale())->first();
        return $shop_category ? $shop_category->name : '';
    }
}
