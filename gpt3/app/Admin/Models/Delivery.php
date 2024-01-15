<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class Delivery
 * @package App\Admin\Models
 * @version January 4, 2020, 4:24 am UTC
 *
 * @property integer shop_id
 * @property integer provider_id
 * @property string code
 * @property boolean is_active
 */
class Delivery extends Model
{

    public $table = 'deliveries';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
        'provider_id',
        'city_id1',
        'city_id2',
        'min_distance',
        'max_distance',
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
        'provider_id' => 'integer',
        'name' => 'string',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'city_id1' => 'required',
        'provider_id' => 'required',
        'is_active' => 'required'
    ];

    
}
