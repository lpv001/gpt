<?php

namespace App\Admin\Repositories;

use App\Admin\Models\District;
use App\Repositories\BaseRepository;

/**
 * Class DistrictRepository
 * @package App\Admin\Repositories
 * @version December 7, 2019, 12:41 pm UTC
*/

class DistrictRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'iso_code',
        'city_province_id',
        'default_name',
        'slug',
        'lat',
        'lng',
        'order',
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
        return District::class;
    }
}
