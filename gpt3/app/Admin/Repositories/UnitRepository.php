<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Unit;
use App\Repositories\BaseRepository;

/**
 * Class UnitRepository
 * @package App\Admin\Repositories
 * @version August 19, 2019, 3:49 pm UTC
*/

class UnitRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return Unit::class;
    }
}
