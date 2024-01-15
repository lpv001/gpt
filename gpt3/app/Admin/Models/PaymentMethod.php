<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class PaymentMethod
 * @package App\Admin\Models
 * @version December 7, 2019, 12:32 pm UTC
 *
 * @property integer shop_id
 * @property string type
 * @property string name
 * @property string description
 * @property string code
 * @property string provider
 * @property boolean is_active
 */
class PaymentMethod extends Model
{

    public $table = 'payment_methods';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'slug',
        'name',
        'flag',
        'is_active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'slug' => 'required',
        'name' => 'required',
    ];
}
