<div class="table-responsive">
    <table class="table table-bordered" id="datatables">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Icon</th>
                <th width="50%">Description</th>
        <!-- <th>Is Active</th> -->
                <th colspan="3">Action</th>
            </tr>
        </thead>
       
    </table>
</div>

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
               $('#datatables').DataTable({
               processing: true,
               serverSide: true,
                bAutoWidth:false,
               ajax: '{{ url('payment-provider') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'icon', name: 'icon' },
                        { data: 'description', name: 'description' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });

        });

        $(document).on('click', '.btn-delete', function (e) { 
            e.preventDefault();

            let id = $(this).data('id');
            let url = "{{ route('payment-provider.destroy', ['paymentId']) }}";

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: url.replace('paymentId', id),
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
