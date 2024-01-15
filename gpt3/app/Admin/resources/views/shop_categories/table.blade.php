<div class="table-responsive">
    <table class="table table-bordered" id="datatables">
        <thead>
            <tr>
              <th>ID</th>
              <th>English Name</th>
              <th>Khmer Name</th>
              <th width="20%">Image</th>
              <th>Action</th>
            </tr>
        </thead>
       
    </table>
</div>

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
               $('#datatables').DataTable({
            //    processing: true,
               serverSide: true,
                bAutoWidth:false,
               ajax: '{{ route("shop-category.index") }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name_en', name: 'name_en' },
                        { data: 'name_km', name: 'name_km' },
                        { data: 'image_name', name: 'image_name' },
                        { data: 'action', name: 'action', orderable: false },
                     ],
                     
                });
        });

        $(document).on('click', '.btn-delete', function (e) { 
            e.preventDefault();
            let id = $(this).data('id');
            let url = "{{ route('shop-category.destroy', ['id']) }}";

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover product!",
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
                            }       
                        }
                    });
                } 
            });
        });
    </script>
@endsection