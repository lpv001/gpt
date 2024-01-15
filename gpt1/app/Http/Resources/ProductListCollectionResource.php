<?php

namespace App\Http\Resources;

use App\Product;
use App\Shop;
use App\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductListCollectionResource extends ResourceCollection
{
    protected $limit;
    protected $page;

    public function request($data)
    {
        $this->limit = $data['limit'];
        $this->page = $data['page'];
        return $this;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        $data['products'] = ProductListCollection::collection($this->collection);
        return [
            'status' =>  true,
            'message'   =>  'Get products successfully',
            'data' =>  $data,
            'request'   =>  [
                'limit' => intval($this->limit),
                'page'  => intval($this->page)
                //,'total_page' => isset($this->limit) && intval($this->limit) != 0 ? round($total_product / intval($this->limit)) : 0,
            ]
        ];
    }
}
