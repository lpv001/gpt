@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Delivery
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                @include('admin::deliveries.show_fields')
                <a href="{!! route('deliveries.index') !!}" class="btn btn-default">Back</a>
            </div>
        </div>
    </div>
@endsection
