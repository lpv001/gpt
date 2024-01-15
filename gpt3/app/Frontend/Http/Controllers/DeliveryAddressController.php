<?php

namespace App\Frontend\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class DeliveryAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $status = false;
        $statusCode = null;
        $message = '';
        try {
            $token = session()->get('token');
            $base_url = env('API_URL');

            $client = new Client(["base_uri" => $base_url . '/delivery-address/' . $id]);
            $response = $client->request('DELETE', "", [
                'headers'  => [
                    'Content-Type' => 'application/json',
                    'authorization' => 'Bearer ' . $token
                ],
                'verify' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $response = json_decode($response->getBody(), true);
        } catch (BadResponseException $badResponse) {
            $badResponse->getCode();
            $response = json_decode($badResponse->getResponse()->getBody()->getContents(), true);
        } catch (Exception $e) {
            $message = $e->getMessage();
            $status = false;
        }

        return response()->json(['status' => $status, 'message' => $message], $statusCode);
    }
}
