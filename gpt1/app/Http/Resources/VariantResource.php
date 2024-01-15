<?php

namespace App\Http\Resources;

// use Illuminate\Http\Resources\Json\ResourceCollection;

use App\ProductImage;
use App\VariantOptionValue;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $option_image = ProductImage::where('option_value_id', $this->option_value_id)->first();

        return [
            'variant_id'   =>  $this->variant_id,
            'option_value_id' => $this->option_value_id,
            'option_value_name' => $this->option_value_name,
            'image_name' => $option_image ? asset($option_image->image_name) : null,
            'variant_price' => $this->variant ? $this->variant->price : 0.00,
            // 'option_value'  => VariantOptionValue::where('variant_id', $this->variant_id)
            //     ->select('option_value_id')
            //     ->get(),
        ];
    }
}
