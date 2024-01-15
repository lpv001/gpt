@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Redeem Promotion Gift
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')

        <div class="box box-primary">
            <div class="box-body mx-4">

                <div class="row mb-4">
                    <div class="col-sm">
                        <div class="pull-left"></div>
                        <div class="pull-right">
                        <a href="{{ route('redeem.create') }}" class="btn btn-sm btn-primary">New <i class="fa fa-plus ml-2"></i></a>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table w-100" id="datatables">
                            <thead>
                                <th>No</th>
                                <th>English Name</th>
                                <th>Khmer Name</th>
                                <th>Code</th>
                                <th>Promotion Type</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Balance</th>
                                {{-- <th>Start Date</th> --}}
                                {{-- <th>End Date</th> --}}
                                <th>image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
    
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
   <script type="text/javascript">
        $(document).ready(function() {
               $('#datatables').DataTable({
               processing: true,
            //    serverSide: true,
                bAutoWidth:false,
               ajax: '{{ route('redeem.index') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name_en', name: 'name_en' },
                        { data: 'name_km', name: 'name_km' },
                        { data: 'code', name: 'code' },
                        { data: 'promotion_type', name: 'promotion_type' },
                        { data: 'value', name: 'value' },
                        { data: 'qty', name: 'qty' },
                        { data: 'balance', name: 'balance' },
                        { data: 'image', name: 'image' },
                        { data: 'is_active', name: 'is_active' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });
        });

        $(document).on('click', '.btn-danger', function (e) { 
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id)
            let url = "{{ route('redeem.destroy', ['id']) }}";

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover it!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: url.replace('id', id),
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response){
                            if (response.status) {
                                swal(`${response.message}`, {
                                    icon: "success",
                                });
                                $("#datatables").DataTable().ajax.reload();
                            } else {
                                swal(`${response.message}`, {
                                    icon: "error",
                                });
                            }     
                        }
                    });
                } 
            });
        });
    </script>
@endsection