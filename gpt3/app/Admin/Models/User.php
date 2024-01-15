<?php

namespace App\Admin\Models;

// use Eloquent as Model;
use Illuminate\Database\Eloquent\Model;
use App\Admin\Models\Shop;
use App\Admin\Models\Role;
use App\Admin\Models\Permission;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Admin\Models
 * @version July 30, 2019, 3:53 pm UTC
 *
 * @property string full_name
 * @property string phone
 * @property string|\Carbon\Carbon phone_verified_at
 * @property string password
 * @property boolean is_active
 * @property boolean phone_verify
 * @property integer membership_id
 * @property integer supplier_id
 * @property string remember_token
 * @property string fcm_token
 */
class User extends Authenticatable
{
    use Notifiable;

    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'full_name',
        'phone',
        'phone_verified_at',
        'password',
        'is_active',
        'phone_verify',
        'membership_id',
        'supplier_id',
        'remember_token',
        'fcm_token',
        'city_province_id',
        'district_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'full_name' => 'string',
        'phone' => 'string',
        'phone_verified_at' => 'datetime',
        'password' => 'string',
        'is_active' => 'boolean',
        'phone_verify' => 'boolean',
        'membership_id' => 'integer',
        'supplier_id' => 'integer',
        'remember_token' => 'string',
        'fcm_token' => 'string',
        'city_province_id'  =>  'integer',
        'district_id'   =>  'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'full_name' => 'required',
        //'phone' => 'required|unique:users',
        //'password' => 'required',
        // 'is_active' => 'required',
        // 'fcm_token' => 'required'
    ];

    /**
     *
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    /**
     *
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    /**
     *
     */
    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }

    /**
     *
     */
    public function shop()
    {
        return $this->hasOne('App\Admin\Models\Shop', 'user_id');
    }

    public function membership()
    {
        return $this->belongsTo('App\Admin\Models\Membership', 'membership_id');
    }
}
