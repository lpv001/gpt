<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\CreateOrderRequest;
use App\Admin\Http\Requests\UpdateOrderRequest;
use App\Admin\Repositories\OrderRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use GuzzleHttp\Exception\BadResponseException;
use App\Admin\Helper\ClientService;

use App\Admin\Models\Order;
use App\Admin\Models\OrderItem;
use App\Admin\Models\Shop;
use App\Admin\Models\User;
use App\Admin\Models\DeliveryProvider;
use App\Admin\Models\PaymentMethod;
use DB;
use DataTables;
use Exception;
use GuzzleHttp\Client;

class OrderController extends AppBaseController
{
    /** @var  OrderRepository */
    private $orderRepository;
    private $orderStatus;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
        $this->orderStatus = [
            0 => 'Initiate',
            1 => 'Accept',
            2 => 'Dilivery',
            3 => 'Complete',
            4 => 'Cancel',
        ];
    }

    /**
     * Display a listing of the Order.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $orders = Order::paginate(20);

        if ($request->ajax()) {
            return Datatables::of(Order::query()->orderBy('id', 'DESC'))
                ->addColumn('shop', function (Order $order) {
                    return Shop::where('id', $order->shop_id)->pluck('name')->toArray();
                })
                ->addColumn('buyer', function(Order $order){
                    return User::where('id', $order->user_id)->pluck('full_name')->toArray();
                })
                ->addColumn('status', function(Order $order){
                    if($order->order_status_id == 0){
                        return '<span class="label label-primary">Initiate</span>';
                    } else if($order->order_status_id == 1){
                        return '<span class="label label-warning">Pending</span>';
                   } else if($order->order_status_id == 2){
                        return '<span class="label label-info">Dilivery</span>';
                    }   else if($order->order_status_id == 3){
                        return '<span class="label label-success">Completed</span>';
                    } else {
                        return '<span class="label label-danger">Cancelled</span>';
                    }
                })
                ->addColumn('action', function($data){
                    return '<div class="btn-group">
                                <a href='.route("orders.show", [$data->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href='.route('orders.edit', [$data->id]).' class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                <button type="button" data-id="'.$data->id.'" class="btn btn-danger btn-xs" id="deleteOrder"><i class="glyphicon glyphicon-trash"></i></button>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $status = [
                    0 => 'Initiate',
                    1 => 'Pending',
                    2 => 'Dilivery',
                    3 => 'Completed',
                    4 => 'Cancelled'
                ];

        return view('admin::orders.index', compact('orders','status'));
    }

    /**
     * Show the form for creating a new Order.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin::orders.create', compact('orders'));
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param CreateOrderRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderRequest $request)
    {
        $input = $request->all();

        $order = $this->orderRepository->create($input);

        Flash::success('Order saved successfully.');

        return redirect(route('orders.index'));
    }

    /**
     * Display the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $url = 'orders/' . $id;
        $basicauth = new Client(['base_uri' => ClientService::getBaseUri()]);
        $newresponse = $basicauth->request(
            'GET',
            '/api/' . $url,
            ['headers' => ClientService::getHeaders()]
        )->getBody()->getContents();
        $response = json_decode($newresponse, true);
        
        $categories = $response['data']['categories'];
        $order = $response['data']['order'];
        $items = OrderItem::where('order_id', $id)->get();
        $user = $response['data']['user'];
        $shop = $response['data']['shop'];
        $discounts = $response['data']['discounts'];
        $deliveryOption = $response['data']['deliveryOption'] ?? [];
        $deliveries = $response['data']['deliveries'] ?? [];
        $payments = $response['data']['payments'] ?? [];
        
        // Admin support only order object, so convert it.
        $order = (object) $order;
        $order_status = $this->orderStatus;
        
        // List
        $shops = Shop::pluck('name', 'id')->toArray();
        $users = User::pluck('full_name', 'id')->toArray();
        $payment_methods = PaymentMethod::pluck('name', 'id')->toArray();
        $deliveryProvider = DeliveryProvider::pluck('name', 'id')->toArray();
        
        //dd($shops);
        
        if (empty($order)) {
            Flash::error('Order not found');
            return redirect(route('orders.index'));
        }
        return view('admin::orders.show', compact('order','items', 'discounts', 'payments', 'payment_methods', 'user', 'shop', 'shops', 'order_status', 'users', 'deliveries', 'deliveryProvider', 'deliveryOption'));
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
      try {
          $url = 'orders/' . $id;
          $basicauth = new Client(['base_uri' => ClientService::getBaseUri()]);
          $newresponse = $basicauth->request(
              'GET',
              '/api/' . $url,
              ['headers' => ClientService::getHeaders()]
          )->getBody()->getContents();
          $response = json_decode($newresponse, true);
          
          $categories = $response['data']['categories'];
          $order = $response['data']['order'];
          //$items = $response['data']['order-item'];
          $items = OrderItem::where('order_id', $id)->get();
          $user = $response['data']['user'];
          $discounts = $response['data']['discounts'];
          $deliveryOption = $response['data']['deliveryOption'] ?? [];
          $deliveries = $response['data']['deliveries'] ?? [];
          $payments = $response['data']['payments'] ?? [];
          
          // Admin support only order object, so convert it.
          $order = (object) $order;
          $order_status = $this->orderStatus;
          
          // List
          $shops = Shop::pluck('name', 'id')->toArray();
          $users = User::pluck('full_name', 'id')->toArray();
          $payment_methods = PaymentMethod::pluck('name', 'id')->toArray();
          $deliveryProvider = DeliveryProvider::pluck('name', 'id')->toArray();
          
          //dd($payment_methods);
          
      } catch (BadResponseException $exception) {
          $message = [];
          if ($exception->getCode() == 500) {
              \Auth::logout();
              return redirect()->to('/login');
          }
          
          if ($exception->getCode() == 401) {
              $message = json_decode($exception->getResponse()->getBody(), true);
              \Session::flash('message', $message['msg']);
          }
      } catch (\Exception $e) {
          return $e->getMessage();
      }
      
      $edit = 1;
      return view('admin::orders.edit', compact(
          'order',
          'items',
          'shops',
          'users',
          'deliveries',
          'discounts',
          'payment_methods',
          'payments',
          'deliveryProvider',
          'deliveryOption',
          'order_status',
          'edit'
      ));
        
      /////////////////////////////////////////////
        /*
        $order = $this->orderRepository->find($id);
        $items = OrderItem::where('order_id', $id)->get();
        $shop = Shop::pluck('name', 'id')->toArray();
        $user = User::pluck('full_name', 'id')->toArray();
        $deliveryProvider = DeliveryProvider::pluck('name', 'id')->toArray();
        $order_status = $this->orderStatus;
        $edit = 1;
        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }
        
        return view('admin::orders.edit', compact(
            'order',
            'items',
            'user',
            'shop',
            'edit',
            'deliveryProvider',
            'order_status'
        ));
        */
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrderRequest $request)
    {
      $order = $this->orderRepository->find($id);
      if (empty($order)) {
          Flash::error('Order not found');
          return redirect(route('orders.index'));
      }
      
      $this->updateOrderStatus($id, $request->user_id, $request->shop_id, $request->order_status_id);
      
      Flash::success('Order updated successfully.');
      return redirect(route('orders.index'));
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     */
    public function updateDev($id, UpdateOrderRequest $request)
    {
        $orderItems = json_decode($request->orderItem);
        $isDeleted = json_decode($request->isDelete);
        $order = $this->orderRepository->find($id);

        if (empty($order)) {
            Flash::error('Order not found');
            return redirect(route('orders.index'));
        }

        $order = $this->orderRepository->update($request->all(), $id);
    
        if($request->has('orderItem')){
            if(count($orderItems) > 0){
                foreach($orderItems as $orderItem)
                {
                    OrderItem::find($orderItem->id)
                        ->update([
                            'quantity' => $orderItem->quantity,
                            'discount' =>  $orderItem->discount
                        ]);
                }
            }
        }

        $this->updateOrderStatus($id, $order->user_id, $order->shop_id, $order->order_status_id);

        //check if request have delete will delete order item table by id
        if($request->has('isDelete')){
            if(count($isDeleted) > 0){
                foreach($isDeleted as $deleted)
                {
                    OrderItem::find($deleted->id)->delete();
                }
            } 
        }
        Flash::success('Order updated successfully.');

        return redirect(route('orders.index'));
    }

    /**
     * Remove the specified Order from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $order = $this->orderRepository->find($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }

        $this->orderRepository->delete($id);

        Flash::success('Order deleted successfully.');

        return redirect(route('orders.index'));
    }

    public function search(Request $request)
    {
        $input = $request->all();
        $orders = DB::table('orders as o')
            ->join('shops as s', 'o.shop_id', 's.id')
            ->join('users as u', 'o.user_id', 'u.id')
            ->where(function ($query) use ($request) {
                if ($request->name) {
                    $query->where('s.name', 'like', '%' . $request->name . '%')
                        ->orWhere('u.full_name', 'like', '%' . $request->name . '%')
                        ->orWhere('o.id', 'like', $request->name);
                }

                if ($request->date) {
                    $query->where('o.date_order_placed', $request->date);
                }

                if ($request->status) {
                    $query->where('o.order_status_id', $request->status);
                }
            })
            ->select('o.*')
            ->paginate(20);
        $status = [
            0 => 'Initiate',
            1 => 'Pending',
            2 => 'Dilivery',
            3 => 'Completed',
            4 => 'Cancelled'
        ];

        return view('admin::orders.index', compact('orders', 'status'));
    }

    /**
     *
     */
    public function updateOrderStatus($order_id, $user_id, $shop_id, $order_status_id)
    {
      try {
        $url = 'orders/update-order?order_id=' . $order_id . '&user_id=' . $user_id . '&shop_id=' . $shop_id . '&order_status_id=' . $order_status_id;
        $basicauth = new Client(['base_uri' => ClientService::getBaseUri()]);
        $newresponse = $basicauth->request(
            'POST',
            '/api/' . $url,
            ['headers' => ClientService::getHeaders()]
        )->getBody()->getContents();
        $response = json_decode($newresponse, true);        
      } catch (BadResponseException $exception) {
          $message = [];
          if ($exception->getCode() == 500) {
              \Auth::logout();
              return redirect()->to('/login');
          }
          
          if ($exception->getCode() == 401) {
              $message = json_decode($exception->getResponse()->getBody(), true);
              \Session::flash('message', $message['msg']);
          }
      } catch (\Exception $e) {
          $message = $e->getMessage();
          return $message;
      }
      
      
      /*
      return view('admin::orders.edit', compact(
          'order',
          'items',
          'shops',
          'users',
          'deliveries',
          'discounts',
          'payment_methods',
          'payments',
          'deliveryProvider',
          'deliveryOption',
          'order_status',
          'edit',
      ));
      */
    }
}
