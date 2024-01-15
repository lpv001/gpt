<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;

class CategoriesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      if (env('APP_ENV') == 'local') {
        $this->image_name = 'local_category.jpg';
      }
      return [
          'id'    =>  $this->id,
          'default_name'  => $this->default_name,
          'image_name' =>  env('PUB_URL') . '/uploads/images/categories/thumbnail/small_' . $this->image_name,
          'sub_categories'  => $this->whenLoaded('categories')
      ];
    }
}
