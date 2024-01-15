<?php

namespace App\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use App\Frontend\Helpers\ClientService;

class OrderController extends Controller
{
    private function getOrderStatusList()
    {
        return [
            0 => __('frontend.order_status_pending'),
            1 => __('frontend.order_status_processing'),
            2 => __('frontend.order_status_dilivery'),
            3 => __('frontend.order_status_completed'),
            4 => __('frontend.order_status_cancelled')
        ];
    }

    public function index()
    {
        $base_url = env('API_URL');
        $token = session()->get('token');
        $orders = [];
        $order_items = [];
        $categories = [];

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'        => 'application/json',
        ];
        
        $status = $this->getOrderStatusList();

        // return $token;
        try {
            $client = new Client();
            $response = $client->request('GET', $base_url . '/orders/get-my-order-list/all', [
                'headers'  => $headers,
                'verify' => false
            ]);
            $response = json_decode($response->getBody(), true);
            if ($response['status']) {
                if (count($response['data']) > 0) {
                    $orders = $response['data']['orders'];
                }
                $categories = $response['data']['categories'];
            }
        } catch (BadResponseException $exception) {
            if ($exception->getCode() == 500) {
                return redirect()->to('/login');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
            // \Auth::logout();
            return redirect('/login');
        }

        return view('frontend::my.account.orders.order', compact('orders', 'status', 'categories'));
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
        $order = [];
        $order_detail = [];
        $user_order = [];
        $status = $this->getOrderStatusList();
        $discount = 0;
        $deliveryOption = [];
        $payments = [];
        $categories = [];
        
        try {
            $url = 'orders/' . $id;
            $basicauth = new Client(['base_uri' => ClientService::getBaseUri()]);
            $access_token = session()->get('token');
            $newresponse = $basicauth->request(
                'GET',
                '/api/' . $url,
                ['headers' => ClientService::getHeaders()]
            )->getBody()->getContents();
            $response = json_decode($newresponse, true);
            $categories = $response['data']['categories'];
            $order = $response['data']['order'];
            $order_detail = $response['data']['order-item'];
            $user_order = $response['data']['user'];
            $discount = $response['data']['discounts'];
            $deliveryOption = $response['data']['deliveryOption'] ?? [];
            $deliveries = $response['data']['deliveries'] ?? [];
            $payments = $response['data']['payments'] ?? [];
            
            // Return now for success
            return view('frontend::my.account.orders.order_detail', compact(
                'categories',
                'order',
                'order_detail',
                'status',
                'user_order',
                'discount',
                'deliveryOption',
                'deliveries',
                'payments'
            ));
        } catch (BadResponseException $exception) {
            $message = [];
            if ($exception->getCode() == 500) {
              $message = $exception->getResponse()->getBody();
              //dd($message);
              \Auth::logout();
              return redirect()->to('/login');
            }
            
            if ($exception->getCode() == 401) {
              $message = json_decode($exception->getResponse()->getBody(), true);
              //dd($message);
              \Session::flash('message', $message['msg']);
            }
        } catch (\Exception $e) {
          //dd($e->getMessage());
            return $e->getMessage();
        }
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
}
