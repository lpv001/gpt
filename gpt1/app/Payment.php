<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'payment_method_id',
        'order_id',
        'amount',
        'account_name',
        'account_number',
        'phone_number',
        'code',
        'screenshot',
        'memo'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public $timestamps = true;

}
