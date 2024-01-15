<div class="table-responsive">
    <table class="table table-bordered" id="memberships-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Key</th>
        <th>Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <!-- <tbody>
        @foreach($memberships as $membership)
            <tr>
                <td>{!! $membership->key !!}</td>
            <td>{!! $membership->name !!}</td>
                <td>
                    {!! Form::open(['route' => ['memberships.destroy', $membership->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('memberships.show', [$membership->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('memberships.edit', [$membership->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody> -->
    </table>
</div>

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
               $('#memberships-table').DataTable({
               processing: true,
            //    serverSide: true,
                bAutoWidth:false,
               ajax: '{{ url('memberships') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'key', name: 'key' },
                        { data: 'name', name: 'name' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });

            let deleteID;
            $('body').on('click', '#delete-membership', function(){
                deleteID = $(this).data('id');

                if(confirm('Are you sure want to delete?')){
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: 'memberships/'+deleteID,
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
