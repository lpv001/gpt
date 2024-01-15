<?php

namespace App\Http\Resources;

use App\Banner;
use App\Brand;
use App\Category;
use App\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;


class HomeCollectionResource extends ResourceCollection
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
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      $promoted_products = $this;
      $new_products = Product::where('is_active', 1)->orderBy('id', 'DESC')
                      ->with('shop')->take($request['new_product_limit'])->get();
      
      // Collect products that skip below
      $skipIDs = [];
      foreach ($promoted_products as $k => $p) {
        $skipIDs[] = $p->id;
      }
      foreach ($new_products as $k => $p) {
        $skipIDs[] = $p->id;
      }
      
      $random_products = Product::inRandomOrder()->with('shop')->skip((intval($this->page) - 1) * intval($this->limit))
        ->whereNotIn('id', $skipIDs)
        ->take(intval($this->limit))
        ->get();
      
      $category = new Category;
      $categories = $category->getCategories(0);
      
      // Brands
      $brand = new Brand;
      $brands =  $brand->getListofBrand();
      $banners =  Banner::get();
      
      return [
          'status'    =>  true,
          'data'      =>  [
              'new_products'  => ProductListCollection::collection($new_products),
              'random_products'  => ProductListCollection::collection($random_products),
              'promoted_products' =>  ProductListCollection::collection($promoted_products),
              'categories'  =>  CategoriesResource::collection($categories),
              'brands'    =>  BrandResource::collection($brands),
              'banners'   =>  BannerResource::collection($banners),
          ],
          'request'   =>  [
              'limit' => intval($this->limit),
              'page'  => intval($this->page),
          ]
      ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        //$response->header('X-Value', 'True');
    }
}
