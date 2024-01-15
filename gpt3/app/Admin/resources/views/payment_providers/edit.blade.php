@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payment Provider {{ $payment->name }}
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               {{-- <div class="row"> --}}
                   {!! Form::model($payment, ['route' => ['payment-provider.update', $payment->id], 'method' => 'patch', 'enctype'=>'multipart/form-data']) !!}
                        @include('admin::payment_providers.fields')
                   {!! Form::close() !!}
               {{-- </div> --}}
           </div>
       </div>
   </div>

   <script>
       $(document).ready(function(){
            let image = {!! json_encode(@$payment)  !!};
            let preloaded = [
                { id : image.id, src:image.icon}
            ];

            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'images',
                preloadedInputName: 'old',
                maxSize: 2 * 1024 * 1024,
                maxFiles: 1
            });
        });
   </script>
@endsection