@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payment Method {{ $paymentMethod->name }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
           
                <div class="row mx-5 my-3">
                    <div class="col-3">
                        <label for="accountName">Name :</label>
                        <div id="accountName">{{ $paymentMethod->name}}</div>
                    </div>
                    <div class="col-3">
                        <label for="accountName">Slug :</label>
                        <div id="accountName">{{ $paymentMethod->slug ?? '' }}</div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
