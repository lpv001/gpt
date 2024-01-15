<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';
    protected $fillable = [
        'shop_id',
        'provider_id',
        'code',
        'flag',
        'is_active'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public $timestamps = false;

    private function getImageURL()
    {
        return env('PUB_URL') . '/uploads/images/payments';
    }

    public function getIdentity()
    {
        return $this->id;
    }

    public function payment_provider()
    {
        return $this->belongsTo('App\PaymentProvider', 'provider_id');
    }

    public function getShopPaymentMethod($shop_id, $type)
    {
        return DB::table('payment_methods as pm')
            ->join('payment_providers as pp', 'pp.id', 'pm.provider_id')
            ->where('pm.shop_id', $shop_id)
            ->select(
                'pm.id',
                'pm.shop_id',
                'pp.name',
                DB::raw('CONCAT("' . $this->getImageURL() . '/", pm.code) AS qr_image'),
                DB::raw('CONCAT("' . $this->getImageURL() . '/", pp.icon) AS icon_image'),
                'pp.description'
            )
            ->get();
    }

    /**
     * Get all of the account for the PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(PaymentAccount::class, 'method_id', 'id');
    }
}
