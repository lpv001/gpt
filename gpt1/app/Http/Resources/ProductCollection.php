<?php

namespace App\Http\Resources;

use App\Category;
use App\ProductImage;
use App\ProductOption;
use App\Shop;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource
{
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

        // If local just use this image
        // if (env('APP_ENV') == 'local') {
        //     $image->image_name = 'local_product_image.jpg';
        // }

        if ($image) {
            if ($this->view == 'detail') {
                $image_name = $image_path . $image->image_name;
            } else {
                $image_name = $image_path . 'thumbnail/medium_' . $image->image_name;
            }
        }

        $shop  = Shop::where('id', $this->shop_id)->first();

        // Get product option images
        $images = [];
        $productOptions = ProductOption::where('product_id', $this->id)->with('option.option_value.image')->get();
        foreach ($productOptions as $key => $product) {
            if ($product->option && $product->option->option_value) {
                if ($product->option->option_value) {
                    foreach ($product->option->option_value as $option_value) {
                        if ($option_value->image) {
                            // $images[] =  [
                            //     'image_name' => $option_value->image->image_name,
                            //     'id' => $option_value->image->id,
                            // ];
                        }
                    }
                }
            }
        }


        $productsImages = ProductImage::where('product_id', $this->id)->select('id', 'image_name', 'option_value_id')->get()->toArray();
        $images = array_merge($productsImages, $images);
        foreach ($images as $k => $v) {
            $images[$k]['image_name'] = $image_path . $images[$k]['image_name'];
        }

        return [
            'id'            =>  $this->id,
            'product_code' => $this->product_code,
            'name'          =>  isset($this->translated_name) ? $this->translated_name : $this->name,
            'unit_price'    =>  $this->unit_price,
            'sale_price'    =>  $this->sale_price,
            'shop_id'       =>  $this->shop_id,
            'shop_name'     =>  $shop ? $shop->name : '',
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
            'images'        =>  $images,
            'options'       =>  isset($this->product_option) ? ProductOptionResource::collection($this->whenLoaded('product_option')) : [],
            'variants'      =>  isset($this->variant_option) ? VariantResource::collection($this->whenLoaded('variant_option')) : [],
            'discount'      =>  0,
            'is_promoted' => $this->is_promoted,
            'is_active' =>  $this->is_active,
            // 'shop' => $this->shop ?? null
        ];
    }
}
