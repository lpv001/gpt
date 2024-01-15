<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
    //
    protected $table = 'order_discounts';
    protected $fillable = [
        'order_id',
        'discount_id',
        'name',
        'value',
        'quantity',
        'flag',
    ];

    public function getDiscounts($order_id, $total)
    {
        $total_discount = 0;
        $items = [];
        $order_discounts = OrderDiscount::where('order_id', $order_id)
        ->where('promotion_translations.locale', \App::getLocale())
        ->join('promotion_translations', 'promotion_translations.promotion_id', '=', 'order_discounts.discount_id')
        ->select(
              'promotion_translations.name as name',
              'order_discounts.value',
              'order_discounts.flag'
        )
        ->get();
        
        if (count($order_discounts) > 0) {
            foreach ($order_discounts as $item) {
                $total_discount += number_format($total * $item->value / 100, 2);
                $items[] = [
                    'name' => $item->name,
                    'value'     =>  number_format($total * $item->value / 100, 2),
                    'symbol'    =>  '%'
                ];
            }
        }

        return ['total_discount' => $total_discount, 'items' => $items];
    }
}
