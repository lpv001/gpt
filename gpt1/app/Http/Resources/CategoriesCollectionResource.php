<?php

namespace App\Http\Resources;

use App\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoriesCollectionResource extends ResourceCollection
{
    protected $page = 0;
    protected $limit = 0;

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
        return [
            'status'    =>  true,
            'msg'   =>  'Get category successfully',
            'data'  =>  ['categories' => CategoriesResource::collection($this->collection)],
            'request'   =>  [
                'limit' => intval($this->limit),
                'page'  => intval($this->page)
            ]
        ];
    }
}
