<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Code;
use App\Repositories\BaseRepository;

/**
 * Class CodeRepository
 * @package App\Admin\Repositories
 * @version August 5, 2023, 10:48 pm UTC
*/

class CodeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'x1_search',
        'x2_search',
        'x1',
        'x2',
        'n1',
        'n2',
        'n3',
        'x4',
        'n4',
        'n5',
        'n6',
        'x4',
        'x1_field',
        'x2_field',
        'n1_field',
        'n2_field',
        'n3_field',
        'x3_field',
        'n4_field',
        'n5_field',
        'n6_field',
        'x4_field',
        'file_prefix',
        'files',
        'gen_progress',
        'ndiff',
        'cdiff',
        'head_id',
        'head',
        'format_data',
        'is_ready',
        'is_used'
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
        return Code::class;
    }
}
