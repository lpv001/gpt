<?php
namespace App\Admin\Models;

use Eloquent as Model;
use App\Admin\Models\User;
use App\Admin\Models\Role;

/**
 * Class Permission
 * @package App\Admin\Models
 * @version May 8, 2021, 3:53 pm UTC
 *
 */
class Permission extends Model
{
    /**
     *
     */
    public function roles() {
        return $this->belongsToMany(Role::class,'roles_permissions');
    }

    /**
     *
     */
    public function users() {
        return $this->belongsToMany(User::class,'users_permissions');
    }
}
