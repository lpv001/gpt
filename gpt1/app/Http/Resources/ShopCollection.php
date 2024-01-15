<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    =>  $this->id,
            'name'  =>  $this->name,
            'about' =>  $this->about,
            'logo_image'    => $this->logo_image,
            'cover_image'   => $this->cover_image,
            'phone' =>  $this->phone,
            'address'   => $this->address,
            'lat'   => $this->lat,
            'lng'   =>  $this->lng,
            'supplier_id'   =>  $this->supplier_id,
            'shop_category_id'  =>  $this->shop_category_id,
            'user_id'   =>  $this->user_id,
            'country_id'    =>  $this->country_id,
            'city_province_id'  => $this->city_province_id,
            'district_id'   =>  $this->district_id,
            'membership_id' =>  $this->membership_id,
            'is_active' =>  $this->is_active,
            'status'    =>  $this->status,
            'membership_name'   =>  '',
            'shop_category_name'    =>  ''
        ];
    }
}
