<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
//use Eloquent as Model;

/**
 * Class Admin
 * @package App\Admin\Models
 * @version July 31, 2019, 7:22 am UTC
 *
 * @property string email
 * @property string password
 * @property string remember_token
 */
class Setting extends Model
{
    public $table = 'settings';



    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'setting_module',
        'setting_key',
        'setting_value',
        'autoload'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'setting_module'    =>  'string',
        'setting_key'       =>  'string',
        'setting_value'     =>  'string',
        'autoload'          => 'integer',

    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'setting_module' => 'required',
        'setting_key' => 'required',
        'setting_value' => 'required'
    ];
}
