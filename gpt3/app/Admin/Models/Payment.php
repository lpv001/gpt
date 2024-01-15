<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class Payment
 * @package App\Admin\Models
 * @version December 7, 2019, 12:32 pm UTC
 *
 * @property integer payment_method_id
 * @property integer order_id
 * @property number amount
 * @property string memo
 */
class Payment extends Model
{

    public $table = 'payments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'payment_method_id',
        'order_id',
        'amount',
        'memo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'payment_method_id' => 'integer',
        'order_id' => 'integer',
        'amount' => 'float',
        'memo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'payment_method_id' => 'required',
        'order_id' => 'required',
        'amount' => 'required',
        'memo' => 'required'
    ];

    
}
