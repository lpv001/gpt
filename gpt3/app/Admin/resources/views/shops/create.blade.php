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
                    {!! Form::open(['route' => 'shops.store', 'method' => 'post', 'files' => true, 'enctype'=>'multipart/form-data', 'id' => 'openshop']) !!}
                        @include('admin::shops.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
