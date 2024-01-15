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
                    {!! Form::open(['route' => 'products.store', 'enctype'=>'multipart/form-data', 'class' => 'form-validation']) !!}

                        @include('admin::products.fields')

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
