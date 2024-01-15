@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payment Account {{ $payment_account->account_name }}
        </h1>
   </section>
   <div class="content">
       {{-- @include('adminlte-templates::common.errors') --}}

       @if ($errors->any())
            <div class="error alert alert-danger">
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

       <div class="box box-primary">
           <div class="box-body">
               {{-- <div class="row"> --}}
                   {!! Form::model($payment_account, ['route' => [@$routeName . '.update', $payment_account->id], 'method' => 'patch', 'enctype'=>'multipart/form-data']) !!}
                        @include(@$viewPath .'.fields')
                   {!! Form::close() !!}
               {{-- </div> --}}
           </div>
       </div>
   </div>
@endsection