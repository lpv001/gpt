@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Orders</h1>
        <h1 class="pull-right">
           {{-- <a class="btn btn-primary pull-right"  style="margin-top: -10px;margin-bottom: 5px" href="{!! route('orders.create') !!}">Add New</a> --}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin::orders.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>

    <!-- Button trigger modal -->
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Mark Order Status As</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body m-4">
          <div class="row">
              <div class="col-sm">
                  @foreach ($status as $key => $item)
                      @php
                          $span = 'primary';
                          if ($key == 1) $span = 'warning';
                          if ($key == 2) $span = 'info';
                          if ($key == 3) $span = 'success';
                          if ($key == 4) $span = 'danger';
                      @endphp
                        <a href="#" class="btn btn-{{$span}} btn-sm btn-status-action" data-status="{{$key}}" data-order_id="0">{{ $item }}</a>
                  @endforeach
                  
                  {{-- <a href="#" class="btn btn-danger btn-sm">Reject Order</a> --}}
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


