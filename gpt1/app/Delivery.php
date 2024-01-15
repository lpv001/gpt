<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    //
    protected $table = 'deliveries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'provider_id',
        'name',
        'min_distance',
        'max_distance',
        'cost',
        'is_active'
    ];
    
    protected $hidden = [
        'updated_at',
        'created_at'
    ];
    
    public $timestamps = true;
    
    public function getIdentity()
    {
        return $this->id;
    }

    public function delivery_provider()
    {
        return $this->belongsTo('App\DeliveryProvider', 'provider_id');
    }
}
