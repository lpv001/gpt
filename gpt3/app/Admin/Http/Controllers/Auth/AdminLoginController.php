<?php

namespace App\Admin\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Redirect;
use Session;


class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     *
     */
    public function showLoginForm()
    {
        return view('admin::auth.login');
    }

    /**
     *
     */
    public function login(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $base_url = env('API_URL');

        $fcm_token = $request->fcm_token;
        if (env('APP_ENV') == 'local') {
            $fcm_token = 'localtoken';
        }
        $data = [
            'phone' => $request->phone,
            'device_type' => 'web',
            'password' => $request->password,
            'fcm_token' => $fcm_token,
            'admin_login' => true
        ];
        
        try {
            $client = new Client();
            
            $response_auth = $client->request('POST', $base_url . '/login', [
                'headers'  => ['Accept' => 'application/json'],
                'json' => $data,
                'verify' => false
            ]);
            $data = json_decode($response_auth->getBody());
  
            if ($data->status) {
                Auth::guard('admin')->loginUsingId($data->data->user->id);
                session()->put('token',  $data->data->token);
                session()->put('user',  $data->data->user);
                session()->put('roles',  $data->data->roles);
            }
        } catch (BadResponseException $exception) {
            $message = $exception->getResponse()->getBody()->getContents();
            $request->session()->flash('errors', $message);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }
        
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     *
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }

    /**
     *
     */
    public function guard()
    {
        return Auth::guard('admin');
    }
}
