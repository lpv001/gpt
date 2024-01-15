@extends('frontend::layouts.main')
@section('content')
    <div class="container my-4">
        <form action="{{ route('excute-order.pay') }}" method="post" class="" id="placeOrder"  enctype="multipart/form-data">
            @csrf
        <div class="row">
            <div class="col-7 card p-4 mr-2">

                <div class="payment-body">
                    {{-- <hr> --}}

                    {{-- Advance --}}
                    <h5>{{ __('frontend.select_payment_option') }}<span class="text-danger">*</span></h5>
                    @include('frontend::carts.checkouts.payments.payment_in_advance', ['payment_option' => @$payment_option ?? null, 'payload' => @$payload ?? null])

                    {{-- GateWay --}}
                    {{-- @if (@$payment_type == 1)
                        <div class="error alert alert-danger">
                            We are sorry. This payment method were under maintaining.
                        </div>
                        @include('frontend::carts.checkouts.payments.payment_gateway')
                    @endif --}}
                        
                </div>
            </div>

            {{-- Right Side --}}
            <div class="col-4 card p-4 ml-2" style="height: 50%">
                <h6 class="text-center">{{ __('frontend.msg_order_procedure_notice') }}</h6>
                <button class="btn btn-xs btn-warning btn-submit w-100">{{ __('frontend.place_order') }}</button>
            </div>
        </div>

        </form>
    </div>

   
@endsection

@section('scripts')
    <script src="{{ asset('js/payments/payment_in_advance.js') }}"></script>
@endsection