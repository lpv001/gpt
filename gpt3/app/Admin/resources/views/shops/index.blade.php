@extends('admin::layouts.app')

@section('content')
  <section class="content-header">
    <h3 class="pull-left">Shops</h3>
  </section>
  
  <div class="content">
      <div class="clearfix"></div>
      @include('flash::message')
      <div class="clearfix"></div>
      
      <div class="box box-primary">
        <div class="box-body">
            @include('admin::shops.search_filter')
        </div>
        <div class="box-body">
          @include('admin::shops.table')
        </div>
      </div>
      
      <div class="text-center">
      </div>
  </div>
  
  <script type="text/javascript">
    $('#date').datepicker({  
       format: 'yyyy-mm-dd'
     });
  </script>  
@endsection

