<?php

namespace App\Frontend\Models;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Models\OrderItem;
use App\Frontend\Models\ProductImage;

class Order extends Model
{
    //
    function getOrderIdAttribute() {
        return str_pad($this->id, 4,'0',STR_PAD_LEFT);
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
        'total'
    ];

    protected $dates = [
        'date_order_placed',
    ];

    /**
     *
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public function order_items(){
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    /**
     *
     */
    public function order_status() {
        return $this->belongsTo(OrderStatus::class);
    }

    /**
     *
     */
    public function shop(){
        return $this->hasMany('App\Shop','shop_id');
    }

    /**
     *
     */
    public function city(){
        return $this->hasMany('App\CityProvinces', 'address_city_province_id');
    }

    /**
     *
     */
    public function district(){
        return $this->hasMany('App\Districts', 'address_district_id');
    }

    /**
     *
     */
    public function getListofStatusOrder($status_id){
        return Order::where('order_status_id', $status_id)->get();
    }
}
