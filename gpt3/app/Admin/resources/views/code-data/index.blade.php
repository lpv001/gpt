@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
      <h1 class="pull-left">Code Data</h1>
    </section>
    
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        
        <div class="box box-primary">
            <div class="box-body">
                @include('admin::code-data.search_filter')
            </div>
            @if(@$codes)
            <div class="box-body">
                @include('admin::code-data.table')
            </div>
            @endif
        </div>
       <div class="text-center">
       </div>
    </div>
    
@endsection
