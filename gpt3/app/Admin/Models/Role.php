<?php
namespace App\Admin\Models;

use Eloquent as Model;
use App\Admin\Models\User;
use App\Admin\Models\Permission;

/**
 * Class Role
 * @package App\Admin\Models
 * @version May 8, 2021, 3:53 pm UTC
 *
 */
class Role extends Model
{
    /**
     *
     */
    public function permissions() {
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }

    /**
     *
     */
    public function users() {
        return $this->belongsToMany(User::class,'users_roles');
    }
}
