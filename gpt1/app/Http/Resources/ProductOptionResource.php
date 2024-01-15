<?php

namespace App\Http\Resources;

use App\ProductOption;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            =>  $this->option_id,
            'name'          =>  $this->option->name,
            'values'        =>  OptionValueResource::collection($this->option->option_value)
        ];
    }
}
