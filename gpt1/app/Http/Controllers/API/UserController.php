<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Nexmo\Laravel\Facade\Nexmo;

class UserController extends Controller
{
    //Status HTTP code
    public $successStatus = 200;
    public $successCreated = 201;

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }

    /**
     * Show a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $users = User::where('is_active', 1)->get();;
            $response = [
                'status'  => true,
                'msg' => __('admin.get_user_success'),
                'data' => [
                    'users' => $users
                ]
            ];
            return response()->json($response, 200);
    }

    /**
     *
     */
    public function show($id){
        $validator = Validator::make(['id' => $id], ['id'=> 'numeric']);
        if($validator->fails())
        {
            $message = implode("", $validator->messages()->all());
            $response = [
                'status' => false,
                'msg' => $message,
                'data' => [],
            ];
            return response()->json($response, 401);
        }

        $users = User::where('id', $id)
                ->where('is_active', 1)
                ->first();
                
        if($users != null)
        {   
            $response = [
            'status'  => true,
            'msg' => __('admin.get_user_success'),
                'data' => [
                    'users' => $users
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status'  => true,
                'msg' => __('admin.get_user_fail'),
                'message' => [
                    'kh' => 'Can\'t find that users',
                    'en' => 'Can\'t find that users',
                    'ch' => 'Can\'t find that users',
                ],
                'data' => [
                    'users' => $users
                ]
            ];
            return response()->json($response, 401);
        }
    } // EOF

    /**
     * Update user profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request){
        try {
            $user = auth()->user();
            $user_id = $user->id;
            
            if($request->hasFile('profile_image')){
                $image_name = 'profile'.time().'.'.request()->profile_image->getClientOriginalExtension();
                $path = $request->file('profile_image')->move(public_path('/uploads/images/users'), $image_name);
                // update the users table
                User::where("id", $user_id)->update(['profile_image' => $image_name]);
                
                // fill in profile
                $user->profile_image = env('PUB_URL') . '/uploads/images/users/' . $image_name;
            }
            
            $response = [
                'status' => true,
                'msg' => 'User was saved successfully.',
                'data' => ['user' => $user]
            ];
            return response()->json($response, $this->successStatus);

        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [],
            ];
            return response()->json($response, 401);
        }
    } //EOF
}
