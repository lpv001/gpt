<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    //

    protected $table = 'delivery_addresses';

    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'note',
        'lat',
        'lng',
        'tag',
        'is_active'
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
