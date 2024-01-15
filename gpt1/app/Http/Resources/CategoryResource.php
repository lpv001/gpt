<?php

namespace App\Http\Resources;

use App\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use DB;
use App;

class CategoryResource extends Resource
{

    /**
     *
     */
    private function getRecursiveChildren($parent)
    {
        $cats = collect([]);
        $tmpcats = collect([]);
        if ($parent->categories) {
            foreach ($parent->categories as $child) {
                if (!$cats->contains($child)) {
                    $cats->push($child);
                }
                $tmpcats = $this->getRecursiveChildren($child);
                if ($tmpcats) {
                    foreach ($tmpcats as $cat) {
                        if (!$cats->contains($cat)) {
                            $cats->push($cat);
                        }
                    }
                }
            }
        }
        return $cats;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $locale = App::getLocale();
        $page = intval($request['page']);
        $limit = intval($request['limit']);

        // get recursive sub categories
        $cats = $this->getRecursiveChildren($this);
        $catIds = [intval($this->id)];
        foreach ($cats as $cat) {
            $catIds[] = $cat->id;
        }
        
        // load products for all categories of requested category
        $products = Product::where('is_active', 1)->with('shop')
                    ->whereIn('category_id', $catIds)
                    ->orderBy('id', 'DESC')
                    ->skip(($page - 1) * $limit)->take($limit)
                    ->get();
        
        
        /* This is old by BTY
        $products = DB::table('products as p')
            ->join('product_translations as pt', 'p.id', '=', 'pt.product_id')
            ->join('category_product as cp', 'p.id', '=', 'cp.product_id')
            ->join('category_translations as c', 'c.category_id', '=', 'cp.category_id')
            ->join('brand_translations as b', 'b.brand_id', '=', 'p.brand_id')
            ->join('unit_translations as u', 'u.unit_id', '=', 'p.unit_id')
            ->select(
                'p.*',
                'pt.name',
                'c.name as category_name',
                'b.name as brand_name',
                'u.name as unit_name'
            )
            ->where([
                ['c.locale', $locale],
                ['b.locale', $locale],
                ['u.locale', $locale],
                ['pt.locale', $locale]
            ])
            ->whereIn('cp.category_id', $catIds)
            ->groupBy('p.id')
            ->skip(($page - 1) * $limit)->take($limit)
            ->get();
        */
        
        return [
            'id'    =>  $this->id,
            'name' => $this->default_name,
            'default_name'  => $this->default_name,
            'image_name' =>  $this->image_name,
            'sub_categories'  => $this->whenLoaded('categories'),
            'products'  => ProductListCollection::collection($products)
        ];
    }
}
