<?php

namespace App\Frontend\Http\Controllers\Auth;

use App\Frontend\Models\User;
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


class LoginController extends Controller
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

    protected $token;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->token = "";
        $this->middleware('guest')->except('logout');
    }


    public function username()
    {
        return 'phone';
    }

    public function showLoginForm()
    {
        // session()
        $previous_url = str_replace(request()->getSchemeAndHttpHost(), '', url()->previous());
        session()->put('pre_url', $previous_url);
        return view('frontend::auth.login');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|numeric',
            'password' => 'required',
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        $base_url = env('API_URL');

        $fcm_token = $request->fcm_token;
        if (env('APP_ENV') == 'local') {
            $fcm_token = 'localtoken';
        }
        $data = [
            'phone' => $request->phone,
            'device_type' => 'web',
            'password' => $request->password,
            'fcm_token' => $fcm_token
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
                Auth::loginUsingId($data->data->user->id);
                session()->put('token',  $data->data->token);
                session()->put('user',  $data->data->user);

                $previous_url = session()->get('pre_url');
                return redirect($previous_url);
            }
        } catch (BadResponseException $exception) {
            $message = $exception->getResponse();
            $message = json_decode($message->getBody()->getContents(), true);
            $request->session()->flash('error', $message['msg']);
            // return redirect()->back()->withErrors(['Phone number or Password are incorrect ! Please try again.']);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        //$this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/');
    }

    public function storeFcmToken(Request $request)
    {
        $this->token = $request->token;
        return $this->token;
    }
}
