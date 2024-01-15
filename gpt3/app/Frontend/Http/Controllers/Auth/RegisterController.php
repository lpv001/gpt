<?php

namespace App\Frontend\Http\Controllers\Auth;

use App\Frontend\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Nexmo\Laravel\Facade\Nexmo;
use Redirect;
use Session;
use Auth;
use Exception;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showRegistrationForm()
    {
        return view('frontend::auth.register');
        //return 'hello';
    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function getOtpCode(Request $request)
    {
        Validator::make($request->all(), [
            'phone' => 'required|min:9|max:10|unique:users,phone',
        ]);

        $phone = $request->phone;
        $password = $request->password;
        $fullName = $request->name;
        $fcmToken = $request->fcm_token;

        $verification = Nexmo::verify()->start([
            'number' => '855' . str_replace(' ', '', $request->phone),
            'brand'  => 'Gangos'
        ]);


        $data = [
            'phone' => $phone,
            'fullName' => $fullName,
            'password' => $password,
            'verifyRequestId' =>  $verification->getRequestId(),
            'fcmToken' => $fcmToken
        ];
        return Redirect::route('otp')->with(['data' => $data]);
        // compact('phone', 'password', 'fullName', 'verifyRequestId'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15', 'unique:users'],
            //'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password' => ['required', 'string', 'min:8'],
            'token' => ['required']
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->all();
        try {
            Validator::make($data, [
                'full_name' => 'required',
                'phone' => 'required|required|min:9|max:10|unique:users,phone',
                'password' => 'required',
                'verify_request_id' => 'required',
                'otp_code' => 'required|min:4|max:4',
                'fcm_token' => 'required',
            ]);


            Nexmo::verify()->check(
                $data['verify_request_id'],
                $data['otp_code']
            );

            User::create([
                'full_name' => $data['name'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'fcm_token' => $data['fcm_token'],
                'device_type' => "web",
                'city_province_id' => 0,
                'district_id' => 0,
                'is_active' => 1,
            ]);
            //retrieve user and redirect log in user by their id
            $user = User::where('phone', $data['phone'])->first();
            Auth::loginUsingId($user->id);

            Auth::logout();

            return redirect()->route('login');
            // return redirect()->intended('/');
        } catch (Exception $e) {
            return redirect()->route('login');
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'full_name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'fcm_token' => $data['token'],
            'city_province_id' => 0,
            'district_id' => 0,
            'is_active' => 1,
        ]);
    }

    public function otp()
    {
        try {
            $data = Session::get('data');
            $phone = $data['phone'];
            $fullName = $data['fullName'];
            $password = $data['password'];
            $verifyRequestId = $data['verifyRequestId'];
            $fcmToken = $data['fcmToken'];

            return view(
                'frontend::auth.getotp',
                compact('phone', 'password', 'fullName', 'verifyRequestId', 'fcmToken')
            );
        } catch (Exception $e) {
            return redirect()->route('login');
        }
    }
}
