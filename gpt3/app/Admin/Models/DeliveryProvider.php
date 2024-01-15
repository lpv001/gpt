<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class DeliveryProvider
 * @package App\Admin\Models
 * @version January 4, 2020, 4:12 am UTC
 *
 * @property string name
 * @property string description
 * @property string icon
 * @property number cost
 * @property boolean is_active
 */
class DeliveryProvider extends Model
{

    public $table = 'delivery_providers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name',
        'description',
        'icon',
        'cost',
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
        'icon' => 'string',
        'cost' => 'float',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'cost' => 'required',
        'is_active' => 'required'
    ];

    
}
