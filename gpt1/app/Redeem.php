<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redeem extends Model
{
    //
    protected $table = 'redeems';
    protected $fillable = [
        'user_id',
        'shop_id',
        'promotion_id',
        'value',
        'qty',
        'note',
    ];

}
