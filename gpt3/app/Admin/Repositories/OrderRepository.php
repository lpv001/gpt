<?php

namespace App\Admin\Repositories;

use App\Admin\Models\Order;
use App\Repositories\BaseRepository;

/**
 * Class OrderRepository
 * @package App\Admin\Repositories
 * @version September 26, 2019, 1:51 pm UTC
*/

class OrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }
}
