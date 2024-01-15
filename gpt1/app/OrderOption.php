<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderOption extends Model
{
    protected $table = 'order_options';
    protected $fillable = [
        'order_id',
        'option_id',
        'name',
        'unit_price',
        'quantity',
        'flag'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public $timestamps = true;

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function getOrderOptionByOrderId($orderId) {
        return DB::table('order_options as oo')
        ->select('oo.option_id as delivery_id', 'oo.name as delivery_name', 'oo.unit_price as delivery_fee')
        ->where('oo.order_id', $orderId)
        ->first();
    }

    public function getOrderOptionById($optionId) {
        return DB::table('order_options as oo')
        ->select('oo.*')
        ->where('oo.id', $optionId)
        ->get();
    }
}
