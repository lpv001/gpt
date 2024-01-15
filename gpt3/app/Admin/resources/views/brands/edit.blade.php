@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Brand
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($brand, ['route' => ['brands.update', $brand->id], 'method' => 'patch', 'enctype'=>'multipart/form-data']) !!}

                        @include('admin::brands.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
   <script>
        $(document).ready(function(){
        let image = {!! json_encode(@$brand)  !!};
        let preloaded = [
            { id : image.id, src: "{{ asset('/uploads/images/brands') }}" + '/' + image.image_name}
        ];
        console.log(preloaded)
        $('.input-images').imageUploader({
            preloaded: preloaded,
            imagesInputName: 'images',
            preloadedInputName: 'old',
            maxSize: 2 * 1024 * 1024,
            maxFiles: 10
        });
    });
   </script>
@endsection
