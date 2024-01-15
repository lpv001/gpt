@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Category
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($category, ['route' => ['categories.update', $category->id], 'method' => 'patch', 'enctype'=>'multipart/form-data']) !!}

                        @include('admin::categories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>

   <script>
    $(document).ready(function(){
        let image = {!! json_encode(@$category)  !!};
        let preloaded = [
            { id : image.id, src: "{{ asset('/uploads/images/categories') }}" + '/' + image.image_name}
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
