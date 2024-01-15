<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItemVariant extends Model
{
    //
    protected $table = 'order_item_variants';
    protected $fillable = [
        'order_item_id',
        'product_id',
        'variant_id',
        'option_value_id',
        'variant_price'
    ];

    protected $casts = [
        'order_item_id' => 'integer',
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'option_value_id' => 'integer',
        'variant_price' => 'double'
    ];

    /**
     * Get the orderItem that owns the OrderItemVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'id');
    }

    public function optionValue()
    {
        return $this->hasOne(OptionValue::class, 'id', 'option_value_id');
    }
}
