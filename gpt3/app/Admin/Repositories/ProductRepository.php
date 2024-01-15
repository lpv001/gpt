<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Product;
use App\Repositories\BaseRepository;

/**
 * Class ProductRepository
 * @package App\Admin\Repositories
 * @version August 5, 2019, 10:48 pm UTC
*/

class ProductRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'product_code',
        'unit_price',
        'sale_price',
        'point_rate',
        'description',
        'unit_id',
        'category_id',
        'flag',
        'is_active',
        'is_promoted'
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
        return Product::class;
    }
}
