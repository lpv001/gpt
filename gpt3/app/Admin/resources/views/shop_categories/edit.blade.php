@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Shop
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($shop_category, ['route' => ['shop-category.update', $shop_category->id], 'method' => 'patch', 'enctype'=>'multipart/form-data']) !!}

                   @include('admin::shop_categories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>

   <script>
       $(document).ready(function(){
        let image = {!! json_encode(@$shop_category)  !!};
        
        let preloaded = [
            {
                id : image.id,
                src : image.image_name,
            }
        ];
           
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