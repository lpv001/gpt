<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use DB;

class Brand extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'slug',
        'image_name',
        'order'
    ];

    public $timestamps = false;

    private function getImageURL()
    {
        return env('PUB_URL') . '/uploads/images/brands';
    }

    /**
     *
     */
    public function getIdentity()
    {
        return $this->id;
    }

    /**
     *
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     *
     */
    public function getListofBrand()
    {
        $locale = App::getLocale();

        return Brand::where([
            ['is_active', 1],
            ['b.locale', $locale]
        ])
            ->join('brand_translations as b', 'b.brand_id', '=', 'brands.id')
            ->select(
                'brands.id',
                'b.name',
                DB::raw('CONCAT("' . $this->getImageURL() . '/thumbnail/small_", brands.image_name) AS image_name')
            )
            ->get();
    }

    /**
     *
     */
    public function getDetailofBrand($brand_id)
    {
        return Brand::findOrFail($brand_id)->select(
            'brands.*',
            DB::raw('CONCAT("' . $this->getImageURL() . '/", brands.image_name) AS image_name')
        )
            ->first();
    }

    /**
     *
     */
    public function getProductbyBrand($brand_id)
    {
        return Product::with('brands')->where('brand_id', $brand_id)
            ->select('brands.*', DB::raw('CONCAT("' . $this->getImageURL() . '/", brands.image_name) AS image_name'))
            ->get();
    }

    /**
     * Get Name of brand
     */
    public function getNameofBrand($name)
    {
        return Category::select('brands.*')->where('brands.is_active', 1)
            ->where('name', 'like', '%' . $name . '%')
            ->select('brands.*', DB::raw('CONCAT("' . $this->getImageURL() . '/", brands.image_name) AS image_name'))
            ->get();
    }
}
