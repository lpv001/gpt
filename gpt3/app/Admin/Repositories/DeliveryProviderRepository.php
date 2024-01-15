<?php

namespace App\Admin\Repositories;

use App\Admin\Models\DeliveryProvider;
use App\Repositories\BaseRepository;

/**
 * Class DeliveryProviderRepository
 * @package App\Admin\Repositories
 * @version January 4, 2020, 4:12 am UTC
*/

class DeliveryProviderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'icon',
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
        return DeliveryProvider::class;
    }
}
