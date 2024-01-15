@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            New Payment Method
        </h1>
    </section>
    <div class="content">
        {{-- @include('adminlte-templates::common.errors') --}}
        @if ($errors->any())
            <div class="error alert alert-danger">
                Please fill the information.
            </div>
        @endif

        <div class="box box-primary">
            <div class="box-body">
                    {!! Form::open(['route' => @$routeName .'.store', 'enctype'=>'multipart/form-data']) !!}

                        @include(@$view .'.fields')

                    {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.input-images').imageUploader({ maxFiles: 1});
        })
    </script>
@endsection


