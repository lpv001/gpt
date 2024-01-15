<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class Order
 * @package App\Admin\Models
 * @version September 26, 2019, 1:51 pm UTC
 *
 * @property integer order_id
 * @property integer product_id
 * @property string name
 * @property float unit_price
 * @property integer quantity
 * @property float discount

 */

class OrderItem extends Model
{
    //
    public $timestamps = false;
    public $table = 'order_items';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'unit_price',
        'quantity',
        'discount',

    ];

    protected $casts = [
        'order_id',
        'product_id',
        'name',
        'unit_price',
        'quantity',
        'discount',
    ];

}
