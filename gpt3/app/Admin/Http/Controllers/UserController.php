<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateUserRequest;
use App\Admin\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Admin\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Admin\Models\Shop;
use App\Admin\Models\User;
use App\Admin\Models\Membership;
use App\Admin\Models\Role;
use App\Admin\Models\Permission;
use DataTables;
use DB;
use Exception;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(User::query())
                ->addColumn('shop', function (User $user) {
                    return $user->shop_id == 0 ? 'None' : Shop::where('id', $user->shop_id)->pluck('name')->toArray();
                })
                ->addColumn('membership', function (User $user) {
                    return $user->membership_id == 0 || !isset($user->membership_id) ? 'Buyer' : Membership::where('id', $user->membership_id)->pluck('name')->toArray();
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                                <a href=' . route("users.show", [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href=' . route('users.edit', [$data->id]) . ' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="' . $data->id . '" data-toggle="modal" data-target="#deleteUser" class="btn btn-danger btn-xs" id="getDeleteId"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $users = $this->userRepository->paginate(20);
        return view('admin::users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $membership = Membership::pluck('name', 'id')->toArray();
        $role = Role::pluck('name', 'id')->toArray();
        $permission = Permission::pluck('name', 'id')->toArray();
        
        return view('admin::users.create', compact('membership', 'role', 'permission'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $ajax_message = [];
        $input = $request->all();
        try {
            $user = $this->userRepository->create([
                'full_name' => $input['full_name'],
                'phone' => $input['phone'],
                'city_province_id'  =>  $request->ajax() ? 0 : 0,
                'district_id'  =>  $request->ajax() ? 0 : 0,
                'membership_id' => null,
                'password' => Hash::make($input['password']),
                'fcm_token' => $input['fcm_token'] ?? 'none',
                'is_active' => $input['is_active'] ?? 1,
                'device_type' => "web"
            ]);

            $users = User::orderBy('id', 'DESC')->select('id', 'full_name')->get();
            $ajax_message = ['status' => 201, 'data' => $users, 'phone' => $user->phone, 'message' => ''];

            // Save user roles
            $role = Role::find($request->role_ids);
            $user->roles()->attach($role);

            // Save user permission
            $permission = Permission::find($request->permission_ids);
            $user->permissions()->attach($permission);

            Flash::success('User saved successfully.');
        } catch (Exception $e) {
            $ajax_message = ['status' => 500, 'data' => [], 'phone' => $input['phone'], 'message' => $e->getMessage()];
            Flash::error($e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json($ajax_message);
        }

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            if ($request->ajax())
                return response()->json(['staus' => 404, 'message' => 'User not found!']);

            return redirect(route('users.index'));
        }

        if ($request->ajax())
            return response()->json(['status' => 200, 'data' => ['user' => $user->full_name, 'phone' => $user->phone]]);

        return view('admin::users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        $edit = 1;
        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        
        // Assign role_ids and permission_ids
        $cids = array();
        foreach ($user->roles as $cat) {
            $cids[] = $cat->id;
        }
        if (count($cids) > 0) {
            $user->role_ids = $cids;
        }
        $cids = array();
        foreach ($user->permissions as $cat) {
            $cids[] = $cat->id;
        }
        if (count($cids) > 0) {
            $user->permission_ids = $cids;
        }

        
        $membership = Membership::pluck('name', 'id')->toArray();
        $role = Role::pluck('name', 'id')->toArray();
        $permission = Permission::pluck('name', 'id')->toArray();

        return view('admin::users.edit', compact('user', 'edit', 'membership', 'role', 'permission'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->find($id);
        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        // Check if phone and password are updating
        $phone = $user->phone;
        $password = $user->password;
        if ($phone != $request->phone) {
            $phone = $request->phone;
        }
        if (strlen($request->password) > 1) {
            $password = Hash::make($request->password);
        }

        $user = $this->userRepository->update([
            'full_name' => $request->full_name,
            'phone' => $phone,
            'membership_id' => $request->membership_id ?? null,
            'password' => $password,
            'is_active' => $request->is_active
        ], $id);

        // Updating user roles
        $user->roles()->sync($request->input('role_ids', []));

        // Updating user permission
        $user->permissions()->sync($request->input('permission_ids', []));

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        // Delete user roles
        $user->roles()->detach();

        // Delete user permissions
        $user->permissions()->detach();

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }
}
