@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Product Price
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($productPrice, ['route' => ['productPrices.update', $productPrice->id], 'method' => 'patch']) !!}

                        @include('admin::product_prices.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection