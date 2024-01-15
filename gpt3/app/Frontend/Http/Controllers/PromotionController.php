<?php

namespace App\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class PromotionController extends Controller
{

    public function verifyPromotionCode(Request $request)
    {
        $status = false;
        $statusCode = null;
        $message = null;
        $data = [];

        try {

            $payload = [
                'code' => $request->code
            ];

            $base_url = env('API_URL');
            $token = session()->get('token');
            // 
            $client = new Client(["base_uri" => $base_url . '/promotions/verify-code']);
            $response = $client->request("GET", "", [
                'headers'  => [
                    'Content-Type' => 'application/json',
                    'authorization' => 'Bearer ' . $token
                ],
                'json' => $payload,
                'verify' => false,
            ]);

            // get status code
            $statusCode = $response->getStatusCode();
            // get response body
            $response = json_decode($response->getBody()->getContents(), true);

            $status = $response['status'];
            $data = $response['data'];
            $message = $response['msg'];
        } catch (BadResponseException $badResponse) {
            $statusCode = $badResponse->getCode();

            $message = json_decode($badResponse->getResponse()->getBody()->getContents(), true);
            $message = $message['msg'];
        } catch (Exception $e) {
            $message = $e->getMessage();
        }


        return response()->json([
            'status' => $status,
            'message' => $message,
            'data'  =>  $data,
        ], $statusCode);
    }
}
