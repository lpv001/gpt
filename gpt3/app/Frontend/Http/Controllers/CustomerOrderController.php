<?php

namespace App\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $status = [
        0 => 'nitiate',
        1 => 'pending',
        2 => 'dilivery',
        3 => 'completed',
        4 => 'cancelled'
    ];

    public function index()
    {
        //
        $orders  = [];
        // $status = [
        //     0 => 'nitiate',
        //     1 => 'pending',
        //     2 => 'dilivery',
        //     3 => 'completed',
        //     4 => 'cancelled'
        // ];
        $status = $this->status;
        try {
            $base_url = env('API_URL');
            $token = session()->get('token');
            $authorization = 'Authorization: Bearer ' . $token;
            $header = ['Authorization' => 'Bearer ' . $token];

            $client = new Client(["base_uri" => $base_url . '/orders/get-shop-order-list/all']);

            $response = $client->request("GET", "", [
                'headers'  => [
                    'authorization' => 'Bearer ' . $token
                ],
                'verify' => false,
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $orders = $response['data']['orders'];
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return view('frontend::my.account.customer_orders.index', compact('orders', 'status'));
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
        $order = [];
        $order_detail = [];
        $user_order = [];
        $status = $this->status;
        $discount = 0;
        $deliveryOption = [];

        try {
            $base_url = env('API_URL');
            $token = session()->get('token');
            $authorization = 'Authorization: Bearer ' . $token;
            $header = ['Authorization' => 'Bearer ' . $token];

            $client = new Client(["base_uri" => $base_url . '/orders/' . $id]);

            $response = $client->request("GET", "", [
                'headers'  => [
                    'authorization' => 'Bearer ' . $token
                ],
                'verify' => false,
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            $order = $response['data']['order'];
            $order = $response['data']['order'];
            $order_detail = $response['data']['order-item'];
            $user_order = $response['data']['user'];
            $discount = $response['data']['discount'];
            $deliveryOption = $response['data']['deliveryOption'] ?? [];
            // return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return view('frontend::my.account.customer_orders.show', compact(
            'order',
            'order_detail',
            'status',
            'user_order',
            'discount',
            'deliveryOption'
        ));
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
    }

    public function orderAction(Request $request)
    {
        try {
            $base_url = env('API_URL');
            $token = session()->get('token');
            $client = new Client(["base_uri" => $base_url . '/orders/update-order']);

            $response = $client->request("POST", "", [
                'headers'  => [
                    'authorization' => 'Bearer ' . $token
                ],
                'json' => [
                    'order_id' =>   $request->order_id,
                    'order_status_id' =>   $request->order_status_id,
                ],
                'verify' => false,
            ]);


            return redirect()->route('orders.index');
        } catch (Exception $e) {
            return redirect()->back();
            // return $e->getMessage();
        }
    }
}
