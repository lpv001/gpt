<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;

use App\Permission;
use App\Role;
use App\User;

class PermissionController extends Controller
{
    public function addAllPermission()
    {
        $dev_permission = Permission::where('slug','create-tasks')->first();
        $manager_permission = Permission::where('slug', 'edit-users')->first();
        
        //RoleTableSeeder.php
        $dev_role = new Role();
        $dev_role->slug = 'developer';
        $dev_role->name = 'Front-end Developer';
        $dev_role->save();
        $dev_role->permissions()->attach($dev_permission);
        
        $manager_role = new Role();
        $manager_role->slug = 'manager';
        $manager_role->name = 'Assistant Manager';
        $manager_role->save();
        $manager_role->permissions()->attach($manager_permission);
        
        $dev_role = Role::where('slug','developer')->first();
        $manager_role = Role::where('slug', 'manager')->first();
        
        $createTasks = new Permission();
        $createTasks->slug = 'create-tasks';
        $createTasks->name = 'Create Tasks';
        $createTasks->save();
        $createTasks->roles()->attach($dev_role);
        
        $editUsers = new Permission();
        $editUsers->slug = 'edit-users';
        $editUsers->name = 'Edit Users';
        $editUsers->save();
        $editUsers->roles()->attach($manager_role);
        
        $dev_role = Role::where('slug','developer')->first();
        $manager_role = Role::where('slug', 'manager')->first();
        $dev_perm = Permission::where('slug','create-tasks')->first();
        $manager_perm = Permission::where('slug','edit-users')->first();
        
        
        //return redirect()->back();
        $response = [
            'status' => true,
            'msg' => 'Update permission successful',
            'data' => []
        ];
        return response()->json($response, 200);
    }
    
    /**
     *
     */
    public function addRole(Request $request)
    {
        $role = new Role();
        $role->slug = $request->input('slug');
        $role->name = $request->input('name');
        $role->save();
        
        $response = [
            'status' => true,
            'msg' => 'Add role successful',
            'data' => ['role' => $role]
        ];
        return response()->json($response, 200);
    }
    
    /**
     *
     */
    public function addPermission(Request $request)
    {
        $perm = new Permission();
        $perm->slug = $request->input('slug');
        $perm->name = $request->input('name');
        $perm->save();
        
        $response = [
            'status' => true,
            'msg' => 'Add permission successful',
            'data' => ['role' => $role]
        ];
        return response()->json($response, 200);
    }
    
    
    /**
     *
     */
    public function Permission(Request $request)
    {
        /*
        $roles = [
                'admin' => 'System Administrator',
                'operator' => 'Market place Operator'
                ];
        // Add some roles
        foreach ($roles as $slug => $name) {
            $request
            $this->add
        }
        dd("OK");
        */
        
        //$user = $request->user();
        //$user = User::where('id',$request->input('user_id'))->first();
        //dd($user->hasRole('developer'));
        
        // Load permissions
        $dev_perm = Permission::where('slug','create-tasks')->first();
        $manager_perm = Permission::where('slug', 'edit-users')->first();
        
        // Load Roles
        $dev_role = Role::where('slug','developer')->first();
        dd($dev_role->users);
        
        $manager_role = Role::where('slug','manager')->first();
        
        // Load User
        $user = User::where('id',$request->input('user_id'))->first();
        
        // Add roles and permission
        $user->roles()->attach($dev_role);
        $user->roles()->attach($manager_role);
        $user->permissions()->attach($dev_perm);
        $user->permissions()->attach($manager_perm);
        
        //return redirect()->back();
        $response = [
            'status' => true,
            'msg' => 'Update permission successful',
            'data' => []
        ];
        return response()->json($response, 200);
    }
}
