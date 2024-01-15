<?php

namespace App\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';
    public $fillable = [
        'user_id',
        'supplier_id',
        'name',
        'about',
        'logo_image',
        'cover_image',
        'phone',
        'country_id',
        'city_province_id',
        'district_id',
        'address',
        'lat',
        'lng',
        'membership_id',
        'is_active',
        'status'
    ];

    protected $appends = ['logo_url', 'cover_url'];

    public function getLogoUrlAttribute()
    {
        return asset('/uploads/images/shops/' . $this->attributes['logo_image']);
    }

    public function getCoverUrlAttribute()
    {
        return asset('/uploads/images/shops/' . $this->attributes['cover_image']);
    }

    /**
     * Get the user associated with the Shop
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
