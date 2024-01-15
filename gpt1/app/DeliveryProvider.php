<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DeliveryProvider extends Model
{
    protected $table = 'delivery_providers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'cost',
        'icon',
        'is_active'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    protected $appends = ['slug'];

    protected $casts = [
        'cost' => 'double',
    ];

    public function getCostAttribute()
    {
        return floatval($this->attributes['cost']);
    }

    public $timestamps = false;
    private $image_path = '/uploads/images/deliveries';

    public function getIdentity()
    {
        return $this->id;
    }

    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }

    public function list()
    {
        return DB::table('delivery_providers as dp')
            ->select(
                'dp.id',
                'dp.name',
                'dp.description',
                'dp.cost as unit_price',
                DB::raw('CONCAT("' . env('PUB_URL') . $this->image_path . '/", dp.icon) AS image_name')
            )
            ->get();
    }

    public function getSlugAttribute()
    {
        $slug = strtolower($this->attributes['name']);
        $slug = str_replace(' ', '_', $slug);
        return $slug;
    }
}
