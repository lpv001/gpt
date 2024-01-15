<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class Order
 * @package App\Admin\Models
 * @version September 26, 2019, 1:51 pm UTC
 *
 * @property integer user_id
 * @property integer shop_id
 * @property string date_order_placed
 * @property string date_order_paid
 * @property integer order_status_id
 * @property integer delivery_option_id
 * @property string address_full_name
 * @property string address_email
 * @property string address_phone
 * @property string address_street_address
 * @property integer address_city_province_id
 * @property integer address_district_id
 * @property string phone_pickup
 * @property string note
 * @property string preferred_delivery_pickup_date
 * @property string preferred_delivery_pickup_time
 * @property integer payment_method_id
 * @property integer delivery_id
 * @property string|\Carbon\Carbon delivery_pickup_date
 * @property float pickup_lat
 * @property float pickup_lon
 * @property float total
 */
class Order extends Model
{

    public $table = 'orders';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
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

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'shop_id' => 'integer',
        'date_order_placed' => 'date',
        'date_order_paid' => 'date',
        'order_status_id' => 'integer',
        'delivery_option_id' => 'integer',
        'address_full_name' => 'string',
        'address_email' => 'string',
        'address_phone' => 'string',
        'address_street_address' => 'string',
        'address_city_province_id' => 'integer',
        'address_district_id' => 'integer',
        'phone_pickup' => 'string',
        'note' => 'string',
        'preferred_delivery_pickup_date' => 'date',
        'preferred_delivery_pickup_time' => 'string',
        'payment_method_id' => 'integer',
        'delivery_id' => 'integer',
        'delivery_pickup_date' => 'datetime',
        'pickup_lat' => 'float',
        'pickup_lon' => 'float',
        'total' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'shop_id' => 'required',
        'date_order_placed' => 'required',
        // 'order_status_id' => 'required',
        'delivery_option_id' => 'required',
        'payment_method_id' => 'required',
        // 'delivery_id' => 'required',
        // 'pickup_lat' => 'required',
        // 'pickup_lon' => 'required',
        //'total' => 'required'
    ];

    public function shop()
    {
        return $this->belongsTo('App\Admin\Models\Shop', 'shop_id');
    }
}
