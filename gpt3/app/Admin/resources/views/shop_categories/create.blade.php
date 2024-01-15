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
                    {!! Form::open(['route' => 'shop-category.store', 'enctype'=>'multipart/form-data']) !!}
                        @include('admin::shop_categories.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.input-images').imageUploader();
        });
    </script>
@endsection

  