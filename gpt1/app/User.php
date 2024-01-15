<?php
namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Permissions\HasPermissionsTrait;
use App\Shop;
use App\Role;
use App\Permission;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasPermissionsTrait; //Import The Trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'phone', 'password','fcm_token', 'device_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','phone_verified_at','phone_verify'
    ];

    /**
     *
     */
    public function permissions() {
        return $this->belongsToMany(Permission::class,'users_permissions');
    }

    /**
     *
     */
    public function roles() {
        return $this->belongsToMany(Role::class,'users_roles');
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
    public function AauthAcessToken(){
        return $this->hasMany('\App\OauthAccessToken');
    }

    /**
     *
     */
    public function orders(){
        return $this->hasMany('App\Order');
    }

    /**
     *
     */
    public function shop(){
        return $this->hasOne('App\Shop', 'user_id');
    }

}