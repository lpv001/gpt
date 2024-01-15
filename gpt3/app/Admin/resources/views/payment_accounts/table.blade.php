<div class="table-responsive">
    <table class="table table-bordered" id="datatables">
        <thead>
            <tr>
                <th></th>
                <th>Account Name</th>
                <th>Account Number</th>
                <th>Phone Number</th>
                <th>Provider</th>
                {{-- <th>Account Type</th> --}}
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
                        { data: 'account_name', name: 'account_name' },
                        { data: 'account_number', name: 'account_number' },
                        { data: 'phone_number', name: 'phone_number' },
                        { data: 'provider_id', name: 'provider_id' },
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
