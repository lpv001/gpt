<?php

namespace App\Admin\Repositories;

use App\Admin\Models\ProductPrice;
use App\Repositories\BaseRepository;
use DB;

/**
 * Class ProductPriceRepository
 * @package App\Admin\Repositories
 * @version January 2, 2020, 2:54 am UTC
*/

class ProductPriceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'type_id',
        'unit_id',
        'city_id',
        'unit_price',
        'sale_price',
        'is_active'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductPrice::class;
    }

    public function getProductPriceByProductId($productId) {
        return DB::table('product_prices as pp')
            ->join('units as un', 'un.id', 'pp.unit_id')
            ->join('memberships as mem', 'mem.id', 'pp.type_id')
            ->leftJoin('city_provinces as city', 'city.id', 'pp.city_id')
            ->where('pp.product_id', $productId)
            ->select('pp.id', 'pp.sale_price', 'pp.unit_price', 'un.id as unit_id', 'un.name as unit_name', 'mem.name as membership',
                'city.default_name')
            ->get();
    }
}
