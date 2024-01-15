@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payment Provider {{ $payment->name }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
           

                <div class="row m-3">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-6">
                                <label for="name">Provider Name :</label>
                                <div>{{ @$payment->name ?? '' }}</div>
                                {{-- <span id="name" class="ml-2">{{ @$payment->name }}</span> --}}
                            </div>
                            <div class="col-7 mt-3">
                                <label for="name">Description :</label>
                                <div>{{ @$payment->description ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <img src="{{ $payment->icon }}" alt="" width="100" height="100">
                    </div>
                </div>
                

                  <div class="row m-3" >
                      <div class="col-6 float-right">
                        <a href="{!! route('payment-provider.index') !!}" class="btn btn-default">Back</a>
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection
