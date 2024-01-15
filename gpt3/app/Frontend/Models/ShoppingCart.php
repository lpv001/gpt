<?php

namespace App\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $table = 'shoppingcart';
    public $fillable = [
        'identifier',
        'instance',
        'content'
    ];

    public function shoppingCart(){
        return $this->belongsTo('Frontend\Models\User');
    }
}
