<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class Membership
 * @package App\Admin\Models
 * @version December 7, 2019, 12:34 pm UTC
 *
 * @property string key
 * @property string name
 */
class Membership extends Model
{

    public $table = 'memberships';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'key',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'key' => 'string',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'key' => 'required',
        'name' => 'required'
    ];

    
}
