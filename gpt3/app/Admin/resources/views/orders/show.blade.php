@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Order
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body" style="padding-left: 5px">
                    @include('admin::orders.show_fields')
            </div>
        </div>
    </div>
@endsection
