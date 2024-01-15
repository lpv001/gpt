<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Membership;
use App\Repositories\BaseRepository;

/**
 * Class MembershipRepository
 * @package App\Admin\Repositories
 * @version December 7, 2019, 12:34 pm UTC
*/

class MembershipRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'key',
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
        return Membership::class;
    }
}
