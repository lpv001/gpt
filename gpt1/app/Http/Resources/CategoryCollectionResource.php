<?php

namespace App\Http\Resources;

use App\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollectionResource extends ResourceCollection
{
    protected $page = 1;
    protected $limit = 12;

    public function request($data)
    {
        $this->page = $data['limit'];
        $this->limit = $data['page'];
        return $this;
    }
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      $cat_resource = CategoryResource::collection($this->collection);
        return [
            'status'    =>  true,
            'msg'   =>  'Get category successfully',
            'data'  =>  $cat_resource[0],
            'request'   =>  [
                'limit' => intval($this->limit),
                'page'  => intval($this->page)
            ]
        ];
    }
}
