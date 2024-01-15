<?php

namespace App\Admin\Repositories;

use App\Admin\Models\City;
use App\Repositories\BaseRepository;

/**
 * Class CityRepository
 * @package App\Admin\Repositories
 * @version December 7, 2019, 12:40 pm UTC
*/

class CityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'iso_code',
        'default_name',
        'slug',
        'lat',
        'lng',
        'is_city',
        'order',
        'is_active',
        'country_id'
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
        return City::class;
    }
}
