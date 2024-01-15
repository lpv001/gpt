<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    //
    protected $table = 'shop_categories';
    protected $fillable = ['order_id', 'image_name', 'is_active'];

    protected $appends = ['name_en', 'name_km'];

    public function getImageNameAttribute()
    {
        return '/uploads/images/shops/categories/' . $this->attributes['image_name'];
    }

    public function getNameEnAttribute()
    {
        $shop_category = ShopCategoryTranslation::where('shop_category_id', $this->attributes['id'])
            ->where('locale', 'en')->first();
        return $shop_category ? $shop_category->name : '';
    }

    public function getNameKmAttribute()
    {
        $shop_category = ShopCategoryTranslation::where('shop_category_id', $this->attributes['id'])
            ->where('locale', 'km')->first();
        return $shop_category ? $shop_category->name : '';
    }
}
