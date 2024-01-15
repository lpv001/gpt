<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Order extends Model
{
    function getOrderIdAttribute()
    {
        return str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
    protected $fillable = [
        'user_id',
        'shop_id',
        'date_order_placed',
        'date_order_paid',
        'order_status_id',
        'delivery_option_id',
        'address_full_name',
        'address_email',
        'address_phone',
        'address_street_address',
        'address_city_province_id',
        'address_district_id',
        'phone_pickup',
        'note',
        'preferred_delivery_pickup_date',
        'preferred_delivery_pickup_time',
        'payment_method_id',
        'delivery_id',
        'delivery_pickup_date',
        'pickup_lat',
        'pickup_lon',
        'point_exchange_rate',
        'total',
        'total_use_points',
        'delivery_address_id'
    ];

    public $timestamps = false;

    protected $dates = [
        'date_order_placed',
    ];

    protected $appends = ['discount'];

    /**
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     *
     */
    public function order_options()
    {
        return $this->hasMany(OrderOption::class);
    }

    /**
     *
     */
    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    /**
     *
     */
    public function shop()
    {
        return $this->hasMany('App\Shop', 'shop_id');
    }

    /**
     *
     */
    public function city()
    {
        return $this->hasMany('App\CityProvinces', 'address_city_province_id');
    }

    /**
     *
     */
    public function district()
    {
        return $this->hasMany('App\Districts', 'address_district_id');
    }

    /**
     *
        all => All Orders
        0 => initiate
        1 => pending
        2 => dilivery
        3 => completed
        4 => cancelled
     */
    public function getStatusList()
    {
        $status_list = [
            'all' => 'all',
            '0,1,2' => 'processing',
            '1' => 'pending',
            '2' => 'dilivery',
            '3' => 'completed',
            '4' => 'cancelled'
        ];
        return $status_list;
    }


    /**
     *
     */
    public function getListofStatusOrder($status_id)
    {
        return Order::where('order_status_id', $status_id)->get();
    }

    /**
     * Get order list
     */
    public function getOrders($ids, $status_id, $page, $limit, $flag)
    {
        $query = null;
        $processing = "0,1,2";
        // where clause
        $where_field = 'shop_id';
        if ($flag == 1) {
            $where_field = 'user_id';
        }

        $query = DB::table('orders')
            ->leftJoin('shops', 'orders.shop_id', '=', 'shops.id')
            ->join('users', 'users.id', 'orders.user_id')
            ->skip(($page - 1) * $limit)->take($limit)
            ->select(
                'users.full_name as user_name',
                'orders.id',
                'shops.name as shop_name',
                'shops.logo_image as shop_logo',
                'orders.*',
                'shops.phone as shop_phone_number'
            )
            ->orderBy('created_at', 'DESC');
        //->get();

        if ($status_id == "all") {
            $where_field = $where_field;
        } else if ($status_id == "processing") {
            $query->whereIn('orders.order_status_id', explode(',', $processing));
        } else {
            $query->where('orders.order_status_id', (int)$status_id);
        }

        if ($flag < 3) {
            $query->whereIn('orders.' . $where_field, $ids);
        }

        return $query->get();
    } // EOF

    /**
     * This is tempoarly get all by mall operator
     */
    public function getAllOrders($status_id, $page, $limit)
    {
        $query = null;
        $processing = "0,1,2";

        $query = DB::table('orders')
            ->leftJoin('shops', 'orders.shop_id', '=', 'shops.id')
            ->join('users', 'users.id', 'orders.user_id')
            ->skip(($page - 1) * $limit)->take($limit)
            ->select(
                'users.full_name as user_name',
                'orders.id',
                'shops.name as shop_name',
                'shops.logo_image as shop_logo',
                'orders.*',
                'shops.phone as shop_phone_number'
            )
            ->orderBy('created_at', 'DESC');

        if ($status_id == "all") {
            $status_id = $status_id;
        } else if ($status_id == "processing") {
            $query->whereIn('orders.order_status_id', explode(',', $processing));
        } else {
            $query->where('orders.order_status_id', (int)$status_id);
        }

        return $query->get();
    } // EOF

    /**
     * Get order list
     */
    public function getOrder($user_id, $status_id, $page, $limit, $flag)
    {
        $query = null;
        $processing = "0,1,2";
        // where clause
        $where_field = 'shop_id';
        if ($flag == 1) {
            $where_field = 'user_id';
        }

        $query = DB::table('orders')
            ->leftJoin('shops', 'orders.shop_id', '=', 'shops.id')
            ->join('users', 'users.id', 'orders.user_id')
            ->skip(($page - 1) * $limit)->take($limit)
            ->select(
                'users.full_name as user_name',
                'orders.id',
                'shops.name as shop_name',
                'shops.logo_image as shop_logo',
                'orders.*',
                'shops.phone as shop_phone_number'
            )
            ->orderBy('created_at', 'DESC');
        //->get();

        if ($status_id == "all") {
            $where_field = $where_field;
        } else if ($status_id == "processing") {
            $query->whereIn('orders.order_status_id', explode(',', $processing));
        } else {
            $query->where('orders.order_status_id', (int)$status_id);
        }

        if ($flag < 3) {
            $query->where('orders.' . $where_field, $user_id);
        }

        return $query->get();
    } // EOF

    /**
     *
     */
    public function getBuyerOrder($user_id)
    {
        $orders = Order::with('order_items')->where('user_id', 1)->get();
    }

    /**
     * TO BE DELETE
     */
    public function getShopByOrderDelete($order_id)
    {
        $order = Order::findOrFail($order_id)->first();
        $shop_name = Shop::where('id', $order->shop_id)->first();
        return $shop_name;
    }

    /**
     * 
     * 
     */
    public function getStat($id, $status = null, $flag)
    {
        // where clause
        $where_field = 'shop_id';
        if ($flag == 1) {
            $where_field = 'user_id';
        }

        if ($flag > 2) {
            $query = DB::table('orders');
        } else {
            $query = DB::table('orders')
                ->where('orders.' . $where_field, $id);
        }

        if ($status != 'all') {
            $query->whereIn('orders.order_status_id', explode(',', $status));
        }
        return $query->count();
    }

    /**
     * Get the user's first name.
     *
     * @param  float  $value
     * @return float
     */
    public function getDiscountAttribute()
    {
        $discount = 0;
        // $promotion = Promotion::where('order_id', $this->attributes['id'])->get();
        // if (count($promotion) > 0) {
        //     foreach ($promotion as $item) {
        //         $discount += $item->unit_price;
        //     }
        // }
        return $discount;
    }
}
