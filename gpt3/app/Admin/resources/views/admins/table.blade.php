<div class="table-responsive">
    <table class="table table-bordered" id="admins-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
        {{--}}<th>Password</th>
        <th>Remember Token</th>--}}
                <th colspan="3">Action</th>
            </tr>
        </thead>
        {{--}}
        <tbody>
        @foreach($admins as $admin)
            <tr>
                <td>{!! $admin->id !!}</td>
                <td>{!! $admin->email !!}</td>
          
                <td>
                    {!! Form::open(['route' => ['admins.destroy', $admin->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('admins.show', [$admin->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('admins.edit', [$admin->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
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
               $('#admins-table').DataTable({
               processing: true,
               serverSide: false,
                bAutoWidth:false,
               ajax: '{{ url('admins') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'email', name: 'email' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });

            let deleteID;
            $('body').on('click', '#delete-admin', function(){
                deleteID = $(this).data('id');

                if(confirm('Are you sure want to delete?')){
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: 'admins/'+deleteID,
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
