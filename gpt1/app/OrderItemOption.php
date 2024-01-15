<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    //
    protected $table = 'order_item_options';
    protected $fillable = [
        'order_item_id',
        'product_id',
        'option_id',
        'option_value_id'
    ];

    protected $casts = [
        'order_item_id' => 'integer',
        'product_id' => 'integer',
        'option_id' => 'integer',
        'option_value_id' => 'integer'
    ];

    /**
     * Get the order_item that owns the OrderItemOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItem::class, 'id', 'order_item_id');
    }

    /**
     * Get the product that owns the OrderItemOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    }

    /**
     * Get the option associated with the OrderItemOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function options()
    {
        return $this->hasOne(Option::class, 'id', 'option_id');
    }

    /**
     * Get the option_value associated with the OrderItemOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function optionValue()
    {
        return $this->hasOne(OptionValue::class, 'id', 'option_value_id');
    }
}
