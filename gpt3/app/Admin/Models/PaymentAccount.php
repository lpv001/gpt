<?php

namespace App\Admin\Models;

use Eloquent as Model;

use function GuzzleHttp\json_decode;

class PaymentAccount extends Model
{

    public $table = 'payment_accounts';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'provider_id',
        'method_id',
        'phone_number',
        'account_name',
        'account_number',
        'display_fields',
        'qr_code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'provider_id'   =>  'integer',
        'method_id' =>  'integer',
        'phone_number' =>  'string',
        'account_name' =>  'string',
        'account_number' =>  'integer',
        'display_fields' =>  'json',
        'qr_code' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    static function rules($request)
    {
        return [
            'provider_id' => 'required',
            'method_id' => 'required',
            'phone_number'  =>  isset($request['account_number']) ? 'nullable' : 'required' . '|numeric',
            'account_name'  =>  isset($request['phone_number']) ? 'nullable' : 'required' . '|string',
            'account_number'  => isset($request['phone_number']) ? 'nullable' : 'required' . '|numeric',
            // 'display_fields'     =>  'required',
        ];
    }


    /**
     * Get the paymentProvider associated with the PaymentAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function paymentProvider()
    {
        return $this->hasOne(PaymentProvider::class, 'id', 'provider_id');
    }
}
