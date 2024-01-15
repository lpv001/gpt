@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payment Method {{ $paymentMethod->name }}
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               {{-- <div class="row"> --}}
                   {!! Form::model($paymentMethod, ['route' => [@$routeName . '.update', $paymentMethod->id], 'method' => 'patch', 'enctype'=>'multipart/form-data']) !!}
                        @include(@$view .'.fields')
                   {!! Form::close() !!}
               {{-- </div> --}}
           </div>
       </div>
   </div>
@endsection