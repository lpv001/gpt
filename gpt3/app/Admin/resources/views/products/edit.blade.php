@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Product
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($product, ['route' => ['products.update', $product->id], 'method' => 'patch', 'enctype'=>'multipart/form-data']) !!}
                        @include('admin::products.fields')
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>

<script>
    $(document).ready(function(){
        let image = {!! json_encode(@$image)  !!};
        let preloaded = [];
        $.each(image, function(key, value){
            preloaded.push({
                id : key,
                src : "{{ asset('uploads/images/products/') }}"+ '/' +value,
            });
        });

        $('.input-images').imageUploader({
            preloaded: preloaded,
            imagesInputName: 'images',
            preloadedInputName: 'old',
            maxSize: 2 * 1024 * 1024,
            maxFiles: 10
        });
    });


    $(document).ready(function(){
            $('.flag-product').each(function(key, ele) {
                console.log($(ele).attr('checked'))
                // console.log($(ele))
                if ( $(ele).attr('checked') === 'checked' )
                    $(ele).closest('tr').find('.flag').val(1);

                // $(ele).closest('tr').find('.flag').val(1);
            });
        });
</script>
@endsection
