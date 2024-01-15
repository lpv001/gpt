@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Delivery
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
             {!! Form::model($delivery, ['route' => ['deliveries.update', $delivery->id], 'method' => 'patch']) !!}
                  @include('admin::deliveries.fields')
             {!! Form::close() !!}
           </div>
       </div>
   </div>
@endsection