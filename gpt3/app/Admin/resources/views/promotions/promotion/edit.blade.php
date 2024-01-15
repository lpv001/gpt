@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Promotion
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body mx-4">
                <div class="row">
                    <div class="col-sm">
                        {!! Form::model($promotion, ['route' => ['promotion.update', $promotion->id], 'method' => 'patch']) !!}
                            @include('admin::promotions.promotion.fields')
                        {!! Form::close() !!}  
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
   
@endsection