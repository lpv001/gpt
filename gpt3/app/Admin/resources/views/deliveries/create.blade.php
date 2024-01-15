@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            New Delivery
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::open(['route' => 'deliveries.store']) !!}
                    @include('admin::deliveries.fields')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
