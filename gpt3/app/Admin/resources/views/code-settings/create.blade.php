@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Create New Codes
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'codes.store', 'enctype'=>'multipart/form-data', 'class' => 'form-validation']) !!}

                        @include('admin::codes.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
