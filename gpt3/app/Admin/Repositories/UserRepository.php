<?php

namespace App\Admin\Repositories;

use App\Admin\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Admin\Repositories
 * @version July 30, 2019, 3:53 pm UTC
*/

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'full_name',
        'phone',
        'phone_verified_at',
        'password',
        'is_active',
        'phone_verify',
        'membership_id',
        'supplier_id',
        'remember_token',
        'fcm_token'
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
        return User::class;
    }
}
