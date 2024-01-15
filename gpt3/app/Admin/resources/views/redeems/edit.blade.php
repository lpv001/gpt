@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Promotion Type
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body mx-4">
                <div class="row">
                    <div class="col-sm">
                        {!! Form::model($promotion, ['route' => ['redeem.update', $promotion->id], 'enctype'=>'multipart/form-data', 'method' => 'patch']) !!}
                            @include('admin::redeems.fields')
                        {!! Form::close() !!}  
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function(){
        let preloaded = [
            { 
                id : {!! $promotion->id !!}, 
                src: "{{ $promotion->image_url }}"
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