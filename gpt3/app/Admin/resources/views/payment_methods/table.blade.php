<div class="table-responsive">
    <table class="table table-bordered" id="datatables">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
       
    </table>
</div>

@section('scripts')
    <script type="text/javascript">
        const url = "{!! url(@$routeName) !!}";
    
        $(document).ready(function() {
               $('#datatables').DataTable({
               processing: true,
               serverSide: true,
                bAutoWidth:false,
               ajax: url,
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'slug', name: 'slug' },
                        { data: 'is_active', name: 'is_active' },
                        { data: 'action', name: 'action', orderable: false },
                     ]
            });

        });

        $(document).on('click', '.btn-delete', function (e) { 
            e.preventDefault();

            let id = $(this).data('id');

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `${url}/${id}`,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response){
                            $("#datatables").DataTable().ajax.reload();    
                        }
                    });
                } 
            });
        });
    </script>
@endsection
