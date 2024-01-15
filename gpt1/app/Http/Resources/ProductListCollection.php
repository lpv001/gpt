<?php

namespace App\Http\Resources;

use App\Category;
use App\ProductImage;
use App\ProductOption;
use App\Shop;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListCollection extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    // public $preserveKeys = true;
    public static $wrap = 'user';
    
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image_path = env('PUB_URL') . '/uploads/images/products/';
        $image_name = null;
        $image = ProductImage::where('product_id', $this->id)->first();
        // Working on image path
        if (!isset($this->view)) {
            $this->view = "list";
        }
        if ($image) {
            if ($this->view == 'detail') {
                $image_name = $image_path . $image->image_name;
            } else {
                $image_name = $image_path . 'thumbnail/medium_' . $image->image_name;
            }
        }
        
        // If local development, set local image
        if (env('APP_ENV') == 'local') {
          $ri = mt_rand(0, 4);
          $image_name = $image_path . 'thumbnail/medium_local_product_image' . $ri . '.jpg';
        }
        
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'unit_price'    =>  $this->unit_price,
            'sale_price'    =>  $this->sale_price,
            'shop_id'       =>  $this->shop_id,
            'shop_name'     =>  $this->shop->name ?? '',
            'image_name'    =>  $image_name,
            'point_rate'    =>  0,
            'flag'          =>  $this->flag,
            'discount'      =>  10,
            'brand_id'      =>  $this->brand_id,
            'brand_name'    =>  $this->brand_name,
            'category_id'   =>  $this->category_id,
            'category_name' =>  $this->category_name,
            'unit_id'       =>  $this->unit_id,
            'unit_name'     =>  $this->unit_name,
            'description'   =>  $this->description,
            'discount'      =>  0,
            'is_promoted' => $this->is_promoted,
            'is_active' =>  $this->is_active,
            'shop' => $this->shop ?? null
        ];
    }
}
