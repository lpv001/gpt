<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderItem extends Model
{
    //
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'unit_id',
        'unit_price',
        'quantity',
        'discount',

    ];

    // append field to model
    protected $appends = ['image_name'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    private $url = '/uploads/images/products';

    public function getOrderItemByOrderId($orderId)
    {
        return DB::table('order_items as oi')
            ->leftJoin('product_images as pi', 'oi.product_id', '=', 'pi.product_id')
            ->leftJoin('order_item_options as oo', 'oo.order_item_id', '=', 'oi.id')
            ->leftJoin('options as o', 'o.id', '=', 'oo.option_id')
            ->leftJoin('option_values as ov', 'ov.id', '=', 'oo.option_value_id')
            ->leftJoin('order_item_variants as v', 'v.order_item_id', '=', 'oi.id')
            ->select('oi.*', 
                     'o.name as option_name',
                     'ov.name as option_value_name',
                     'v.variant_price',
                    DB::raw('CONCAT("' . env('PUB_URL') . $this->url . '/thumbnail/small_", pi.image_name) AS image_name'))
            ->groupBy('oi.id')
            ->where('oi.order_id', $orderId)
            ->get();
        /*
        return DB::table('order_items as oi')
            ->leftJoin('product_images as pi', 'oi.product_id', '=', 'pi.product_id')
            ->select('oi.*', DB::raw('CONCAT("' . env('PUB_URL') . $this->url . '/thumbnail/small_", pi.image_name) AS image_name'))
            ->groupBy('oi.id')
            ->where('oi.order_id', $orderId)
            ->get();
        */
    }

    public function getOrderItemById($orderItem_Id)
    {
        return DB::table('order_items as oi')
            ->leftJoin('product_images as pi', 'oi.product_id', '=', 'pi.product_id')
            ->select('oi.*', DB::raw('CONCAT("' . env('PUB_URL') . $this->url . '/", pi.image_name) AS image_name'))
            ->where('oi.id', $orderItem_Id)
            ->get();
    }

    // set into field
    public function getImageNameAttribute()
    {
        if (array_key_exists('product_id', $this->attributes)) {
            $product = Product::find($this->attributes['product_id'])
                ->with('image')
                ->first();

            return isset($product->image) ? asset($this->url . '/' . $product->image->image_name) : null;
        }

        return null;
    }

    /**
     * Get all of the orderItemOption for the OrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItemOption()
    {
        return $this->hasMany(OrderItemOption::class, 'order_item_id', 'id');
    }

    /**
     * Get all of the variants for the OrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(OrderItemVariant::class, 'order_item_id', 'id');
    }
}
