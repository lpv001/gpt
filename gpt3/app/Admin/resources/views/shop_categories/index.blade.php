@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
            <h3 class="pull-left">Shop Categories</h3>
             <div class="pull-right">
                <a class="btn btn-primary pull-right"  href="{!! route('shop-category.create') !!}">Add New</a>
            </div>
            
    </section>
    
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin::shop_categories.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>

@endsection

