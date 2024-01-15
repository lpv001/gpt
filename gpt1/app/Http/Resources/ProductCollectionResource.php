<?php

namespace App\Http\Resources;

use App\Product;
use App\Shop;
use App\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollectionResource extends ResourceCollection
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
        $limit = 12;
        $page = 1;
        
        $shop = Shop::where('id', $this->collection[0]->shop_id)->get();
        $seller_recommand = Product::where('shop_id', $this->collection[0]->shop_id)->with('shop')->take($limit)->get();
        $related_product = Product::inRandomOrder()->where('category_id', $this->collection[0]->category_id)->with('shop')->take($limit)->get();
        
        $data = [];
        $data['product'] = ProductCollection::collection($this->collection);
        
        if (count($this->collection) == 1) {
            $data['shop'] = ShopCollection::collection($shop);
            $data['seller_product'] = ProductListCollection::collection($seller_recommand);
            $data['seller_products'] = $data['seller_product'];
            $data['related_product'] = ProductListCollection::collection($related_product);
            $data['related_products'] = $data['related_product'];
        }
        return [
            'status' =>  true,
            'message'   =>  'Get products successfully',
            'data' =>  $data,
            'request'   =>  [
                'limit' => intval($this->limit),
                'page'  => intval($this->page),
                //'total_page' => isset($this->limit) && intval($this->limit) != 0 ? round($total_product / intval($this->limit)) : 0,
            ]
        ];
    }
}
