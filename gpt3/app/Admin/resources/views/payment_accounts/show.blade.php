@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payment Account {{ $payment_account->account_name }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
           
                <div class="row mx-5 my-3">
                    <div class="col-3">
                        <label for="accountName">Shop :</label>
                        <div id="accountName">Company</div>
                    </div>
                    <div class="col-3">
                        <label for="accountName">Phone Number :</label>
                        <div id="accountName">{{ $payment_account->phone_number ?? '' }}</div>
                    </div>
                </div>

                <div class="row mx-5 my-3">
                    <div class="col-3">
                        <label for="accountName">Account Name :</label>
                        <div id="accountName">{{ $payment_account->account_name ?? '' }}</div>
                    </div>
                    <div class="col-3">
                        <label for="accountName">Account Number :</label>
                        <div id="accountName">{{ $payment_account->account_number ?? '' }}</div>
                    </div>
                </div>

                <div class="row mx-5 my-3">
                    <div class="col-3">
                        <label for="accountName">Payment Provider :</label>
                        {{-- {{dd($payment_account->paymentProvider)}} --}}
                        <div id="accountName">{{ $payment_account->paymentProvider ? $payment_account->paymentProvider->name : '' }}</div>
                    </div>
                    <div class="col-3">
                        <label for="qrCode">QR Code :</label>
                        <div></div>
                        <img 
                            id="qrCode"
                            src="{{ asset('uploads/images/payments/qrcodes/' . $payment_account->qr_code) }}" 
                            width="50%"
                            alt="">
                        {{-- <div id="accountName">{{ $payment_account->qr_code ?? '' }}</div> --}}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
