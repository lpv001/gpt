<div class="table-responsive">
    <table class="table table-bordered" id="deliveries-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Provider</th>
                <th>From City</th>
                <th>To City</th>
                <th>Min Distance</th>
                <th>Max Distance</th>
                <th>Cost</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        {{-- Data goes here --}}
    </table>
</div>

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
               $('#deliveries-table').DataTable({
               processing: true,
               serverSide: true,
                bAutoWidth:false,
               ajax: '{{ url('deliveries') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'provider', name: 'provider' },
                        { data: 'from_city', name: 'from_city' },
                        { data: 'to_city', name: 'city_id2' },
                        { data: 'min_distance', name: 'min_distance' },
                        { data: 'max_distance', name: 'max_distance' },
                        { data: 'cost', name: 'cost' },
                        { data: 'action', name: 'action', orderable: false },
                     ]
            });

            let deleteID;
            $('body').on('click', '#deletedeliverie', function(){
                deleteID = $(this).data('id');

                if(confirm('Are you sure want to delete?')){
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: 'deliveries/'+deleteID,
                        method: 'POST',
                        success: function(result) {
                            window.location.href = window.location.href;
                        },
                        error: function (error) {
                            alert('error; ' + eval(error));
                        }
                    });
                }
            });
        });
    </script>
@endsection
