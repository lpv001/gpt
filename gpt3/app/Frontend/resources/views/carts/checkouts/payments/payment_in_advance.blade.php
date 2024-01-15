{{-- <hr> --}}
<div class="row mb-2">
    <div class="col-12 my-2">
        <div class="d-flex rounded py-3">
            @if ((@$payment_option))
                @foreach ($payment_option as $payment)
                    <div 
                        class="col-3 text-center border mr-3 px-3 rounded py-2 account-provider" 
                        data-id="{{ $payment['id'] }}"
                        data-phone="{{ $payment['phone_number']}}" 
                        data-name="{{ $payment['account_name'] }}" 
                        data-account="{{ $payment['account_number'] }}"
                        data-body="{{ json_encode($payment['display_fields'], true) }}"
                        data-qrcode="{{ $payment['qr_code'] }}"
                        >

                            <img src="{{ $payment['provider']['icon'] }}" alt="" width="100%" height="70%">
                            <div>{{$payment['provider']['name']}}</div>
                    </div>
                @endforeach
                
                <input type="hidden" name="payment_account_id" value="">
            @endif
        </div>

    </div>
</div>
<hr>

<div class="row" id="p-body">
    <div class="col-12" style="background-color: #9ff08d !important;">
        <div class="row">
            <div class="col-8 rounded px-3 py-2 mt-2">
                <div><h5>{{ __('frontend.payee_information') }}</h5></div>
                <div class="row">
                    <div class="col-4">{{ __('frontend.account_name') }}</div>
                    <div class="col-8" id="payeeAccountName">: </div>
                </div>
                <div class="row">
                    <div class="col-4">{{ __('frontend.account_number') }}</div>
                    <div class="col-8" id="payeeAccountNumber">: </div>
                </div>
                <div class="row">
                    <div class="col-4">{{ __('frontend.phone_number') }}</div>
                    <div class="col-8" id="payeePhoneNumber">: </div>
                </div>
                <div class="row">
                    <div class="col-4 font-weight-bold">{{ __('frontend.order_total') }}</div>
                    <div class="col-8 font-weight-bold" id="paymentOrderTotal">: ${{ $payload['order_total']}}</div>
                </div>
            </div>
            <div class="col-4">
                <img src="" alt="" id="qrCode">
            </div>
        </div>
    </div>
    <hr>

    <div class="col-12  bg-light">
        <div class="row">
            <div class="col-12 rounded px-3 py-2 mt-2">
                <div><h5>{{ __('frontend.payer_information') }}</h5></div>
                <div class="row">
                    <div class="col-6">
                        <div>{{ __('frontend.account_name') }}:</div>
                        <input type="text" class="form-control" name="payment_account_name" value="" placeholder="{{ __('frontend.account_name_placeholder') }}">
                    </div>
                    <div class="col-6">
                        <div>{{ __('frontend.account_number') }}:</div>
                        <input type="text" class="form-control" name="payment_account_number" value="" placeholder="{{ __('frontend.account_number_placeholder') }}">
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-6">
                        <div>{{ __('frontend.phone_number') }}:</div>
                        <input type="text" class="form-control" name="payment_phone_number" value="" placeholder="{{ __('frontend.phone_number_placeholder') }}">
                    </div>
                    <div class="col-6">
                        <div>{{ __('frontend.payment_code') }}:</div>
                        <input type="text" class="form-control" name="payment_code" value="" placeholder="{{ __('frontend.payment_code_placeholder') }}">
                    </div>
                </div>
            </div>
        </div>
        {{--
        <div class="row">
            <div class="col-12 mb-4">
                <div>{{ __('frontend.payment_image') }}:</div>
                <div class="input-images"></div>
            </div>
        </div>
        --}}
    </div>
</div>
