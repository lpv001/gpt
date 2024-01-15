<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Delivery;
use App\Repositories\BaseRepository;

/**
 * Class DeliveryRepository
 * @package App\Admin\Repositories
 * @version January 4, 2020, 4:24 am UTC
*/

class DeliveryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'provider_id',
        'city_id1',
        'city_id2',
        'min_distance',
        'max_distance',
        'cost',
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
        return Delivery::class;
    }
}
