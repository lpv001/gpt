<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class Code
 * @package App\Admin\Models
 * @version August 5, 2023, 10:48 pm UTC
 *
 * @property string code
 * @property integer is_used
 */
class Code extends Model
{

    public $table = 'codes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'x1_search',
        'x2_search',
        'x1',
        'x2',
        'n1',
        'n2',
        'n3',
        'x3',
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
        'is_used',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'format_data'  => 'string',
        'x1'  => 'string',
        'x2'  => 'string',
        'n1'  => 'string',
        'n2'  => 'string',
        'n3'  => 'string',
        'x3'  => 'string',
        'n4'  => 'string',
        'n5'  => 'string',
        'n6'  => 'string',
        'x4'  => 'string',
        'x1_field'  => 'string',
        'x2_field'  => 'string',
        'n1_field'  => 'string',
        'n2_field'  => 'string',
        'n3_field'  => 'string',
        'x3_field'  => 'string',
        'n4_field'  => 'string',
        'n5_field'  => 'string',
        'n6_field'  => 'string',
        'x4_field'  => 'string',
        'file_prefix' => 'string',
        'files' => 'string',
        'gen_progress' => 'string',
        'ndiff' => 'integer',
        'cdiff' => 'integer',
        'head_id' => 'integer',
        'head'  => 'string',
        'is_ready' => 'integer',
        'is_used' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'x1' => 'required|min:1|max:1'
    ];

}
