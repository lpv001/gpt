<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Admin
 * @package App\Admin\Models
 * @version Mar 28, 2021, 7:22 am UTC
 *
 */
class Point extends Model
{
    public $table = 'points';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'user_id',
        'shop_id',
        'order_id',
        'title',
        'total',
        'flag',
        'status'
    ];

    // protected $appends = ['title', 'total'];
}
