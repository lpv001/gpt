<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class LogoutController extends Controller
{
    public $successStatus = 200;

    public function logout()
    {
        try {
            if (Auth::check()) {
                Auth::user()->AauthAcessToken()->delete();
                User::where('id', Auth::user()->id)->update(['fcm_token' => null, 'is_active' => false]);
            }
            
            $response = [
                'status' => true,
                'msg' => 'You have logged out successfully.',
                'data' => [],
            ];
            return response()->json( $response, $this->successStatus);
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'msg' => $e->getMessage(),
                'data' => [
                    
                ],
            ];
            return response()->json($response, 401);
        }
    }
}
