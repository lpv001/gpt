<div class="table-responsive">
    <table class="table table-bordered" id="deliveryProviders-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Icon</th>
                <th>Cost</th>
                <th>Is Active</th>
                <th>Created At</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        {{--}}
        <tbody>
        @foreach($deliveryProviders as $deliveryProvider)
            <tr>
                <td>{!! $deliveryProvider->name !!}</td>
            <td>{!! $deliveryProvider->description !!}</td>
            <td>{!! $deliveryProvider->icon !!}</td>
            <td>{!! $deliveryProvider->cost !!}</td>
            <td>{!! $deliveryProvider->is_active !!}</td>
                <td>
                    {!! Form::open(['route' => ['deliveryProviders.destroy', $deliveryProvider->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('deliveryProviders.show', [$deliveryProvider->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('deliveryProviders.edit', [$deliveryProvider->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
        --}}
    </table>
</div>

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
               $('#deliveryProviders-table').DataTable({
               processing: true,
               serverSide: true,
                bAutoWidth:false,
               ajax: '{{ url('deliveryProviders') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'description', name: 'description' },
                        { data: 'icon', name: 'icon' },
                        { data: 'cost', name: 'cost' },
                        { data: 'is_active', name: 'is_active' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });

            let deleteID;
            $('body').on('click', '#deleteOrder', function(){
                deleteID = $(this).data('id');

                if(confirm('Are you sure want to delete?')){
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: 'deliveryProviders/'+deleteID,
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