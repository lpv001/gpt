<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    //
    protected $table = 'payment_details';
    protected $fillable = [
        'payment_id',
        'payment_account_id',
        'payment_provider_id',
        'account_name',
        'phone_number',
        'account_number',
        'code',
        'screenshot',
    ];
}
