<?php

namespace App\Frontend\Http\Controllers;

use App\Frontend\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FirebaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storeFCM(Request $request)
    {
        $validated = $request->validate([
            'fcm_token' => 'required',
        ]);

        try {
            $base_url = env('API_URL');
            $headers = [
                'Authorization' => 'Bearer ' . session()->get('token'),
                'Content-Type'        => 'application/json',
            ];

            $client = new Client(["base_uri" => $base_url . '/store-fcm-token?fcm_token=' . $request->fcm_token]);
            $token = session()->get('token');

            $response = $client->request("POST", "", [
                'headers'  => [
                    'authorization' => 'Bearer ' . $token
                ],
                'verify' => false,
            ]);
            $response = json_decode($response->getBody(), true);

            if ($response['status']) {
                return response()->json($response, 200);
            }

            return response()->json($response, 500);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ], 500);
        }
    }
}
