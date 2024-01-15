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
                        {!! Form::open(['route' => 'point.store']) !!}
                            @include('admin::promotions.point.fields')
                        {!! Form::close() !!}  
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
   
@endsection