<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentProvider extends Model
{
    protected $table = 'payment_providers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'icon',
        'is_active'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public $timestamps = false;
    private $image_path = '/uploads/images/payments';

    public function getIdentity()
    {
        return $this->id;
    }

    public function payment_methods()
    {
        return $this->hasMany('App\PaymentMethod');
    }

    public function list()
    {
        return DB::table('payment_providers as pp')
            ->select(
                'pp.id',
                'pp.name',
                'pp.description',
                DB::raw('CONCAT("' . env('PUB_URL') . $this->image_path . '/", pp.icon) AS icon')
            )
            ->get();
    }

    public function getIconAttribute()
    {
        return asset('uploads/images/payments/icons/' . $this->attributes['icon']);
    }
}
