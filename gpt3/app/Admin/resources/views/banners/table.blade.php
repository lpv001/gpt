<div class="table-responsive">
    <table class="table table-bordered" id="datatables">
        <thead>
            <tr>
              <th>ID</th>
              <th width="30%">Title</th>
              <th>Image</th>
              <th>Expiry Date</th>
              <th width="8%">Status</th>
              <th colspan="3">Action</th>
            </tr>
        </thead>
    </table>
</div>

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
               $('#datatables').DataTable({
            //    processing: true,
            //    serverSide: false,
                bAutoWidth:false,
               ajax: "{{ route('banners.datatables') }}",
               columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'title', name: 'title' },
                        { data: 'image', name: 'image' },
                        { data: 'expiry_date', name: 'expiry_date' },
                        { data: 'is_active', name: 'is_active' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });
        });

        $(document).on('click', '.btn-delete', function (e) { 
            e.preventDefault();
            let id = $(this).data('id');
            let url = "{{ route('banners.destroy', ['id']) }}";

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
                            if (response.status == 200 ) {
                                swal(`${response.message}`, {
                                    icon: "success",
                                });
                                $("#datatables").DataTable().ajax.reload();
                            }       
                        }
                    });
                } 
            });
        });

        $(document).on('click', '.product-status', function() {
            var id = $(this).data('id');
            var status = $(this).data('type') == 'active' ? 0 : 1 ;
            var message = $(this).data('type') == 'active' ? 'Are you sure to deactivate Banner?' : 'Are you sure to activate Banner?';
            
            swal( 'Activate', message)
            .then((update) => {
                if (update) {

                    $.ajax({
                        url: "{{ route('banners.status') }}",
                        type: 'POST',
                        data: {
                            id: id,
                            status: status,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response){
                            if (response.status == 200 ) {
                                $("#datatables").DataTable().ajax.reload();
                            }       
                        }
                    });
                } 
            });;
        });
    </script>
@endsection