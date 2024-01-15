<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionValueResource extends JsonResource
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
        return [
            'id'    =>  $this->id,
            'name'  =>  ucfirst($this->name),
            'image_name' =>  $this->image ? $image_path . $this->image->image_name : null,
            'image_id' => $this->image ? $this->image->id : null
        ];
    }
}
