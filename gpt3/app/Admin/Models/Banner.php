<?php

namespace App\Admin\Models;

use Eloquent as Model;

/**
 * Class Banner
 * @package App\Admin\Models
 * @version August 9, 2019, 4:25 pm UTC
 *
 */
class Banner extends Model
{

    public $table = 'banners';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'title',
        'description',
        'image',
        'target_url',
        'expiry_date',
        'is_active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'image' => 'string',
        'target_url' => 'string',
        'expiry_date' => 'string',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    // public static $rules = [
        // 'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5000',
        // 'images' => 'required',
        // 'target_url' => 'required',
        // 'is_active' => 'required'
    // ];
}
