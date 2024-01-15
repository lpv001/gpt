@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Edit Code
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($code, ['route' => ['codes.update', $code->id], 'method' => 'patch', 'enctype'=>'multipart/form-data']) !!}
                        @include('admin::codes.fields')
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>

@endsection
