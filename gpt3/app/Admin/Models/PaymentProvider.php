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
class PaymentProvider extends Model
{

    public $table = 'payment_providers';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'name',
        'description',
        'icon',
        'is_active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'code' => 'string',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'icon' => 'nullable|image|mimes:jpg,jpeg,png|max:5000',
    ];


    public function getIconAttribute()
    {
        return asset('uploads/images/payments/icons/' . $this->attributes['icon']);
    }
}
