<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{
    //

    protected $table = 'payment_accounts';

    protected $fillable = [
        'shop_id',
        'provider_id',
        'method_id',
        'phone_number',
        'account_name',
        'account_number',
        'display_fields',
        'qr_code',
    ];


    protected $hiddens = ['created_at', 'updated_at'];

    protected $casts = [
        'display_fields' => 'array'
    ];

    public function getDisplayFieldsAttribute()
    {
        $json = json_decode($this->attributes['display_fields'], true);
        $json = json_decode($json, true);
        return $json;
    }

    /**
     * Get the provider that owns the PaymentAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class, 'provider_id');
    }

    public function getQrCodeAttribute()
    {
        return asset('uploads/images/payments/qrcodes/' . $this->attributes['qr_code']);
    }
}
