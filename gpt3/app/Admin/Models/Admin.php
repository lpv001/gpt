<?php

namespace App\Admin\Models;

// use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Foundation\Auth\User as Authenticatable;

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
class Admin extends Model
{

    // use Notifiable;


    public $table = 'admins';



    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'email',
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email' => 'string',
        'password' => 'string',
        'remember_token' => 'string'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'email' => 'required',
        'password' => 'required'
    ];
}
