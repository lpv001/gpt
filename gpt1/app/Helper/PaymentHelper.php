<?php

namespace App\Helper;

use App\Payment;
use App\PaymentAccount;
use App\PaymentDetail;

class PaymentHelper
{

    // payment 
    static function payment($orderId, $paymentMethodId, $amount, $memo)
    {
        $payment = Payment::create([
            'payment_method_id' => $paymentMethodId,
            'order_id'  => $orderId,
            'amount' => $amount,
            'memo' => $memo,
        ]);

        return $payment;
    }

    // Excute Payment Detail
    static function savePaymentDetail($payment)
    {
        $payment_account = PaymentAccount::find($payment['account_id']);

        if (!$payment_account)
            return false;
        $screen_shot = '';
        $path = public_path('uploads/payments/screenshots');
        $screen_shot = FileUploadHelper::uploadImage($payment['screen_shot'], $path);

        PaymentDetail::create([
            'payment_id'    =>  $payment['payment_id'],
            'payment_account_id' => $payment_account->id,
            'payment_provider_id'   =>  $payment_account->provider_id,
            'account_name'  =>  $payment['buyer_account_name'],
            'account_number'    =>  $payment['buyer_account_number'],
            'phone_number'  =>  $payment['buyer_phone_number'],
            'code'  =>  $payment['payment_code'],
            'screenshot'  => $screen_shot ?? ''
        ]);

        return true;
    }
}
