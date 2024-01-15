<div class="table-responsive">
    <table class="table table-bordered" id="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>English Name</th>
                <th>Khmer Name</th>
                <th>Decription</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (@$units)
                @foreach ($units as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->name_en ?? '' }}</td>
                        <td>{{ $item->name_km ?? '' }}</td>
                        <td>{{ $item->description ?? '' }}</td>
                        <td>
                            {!! Form::open(['route' => ['units.destroy', $item->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                {{-- <a href="{!! route('units.show', [$item->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                                <a href="{!! route('units.edit', [$item->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach                
            @endif
        </tbody>
    </table>
</div>

@section('scripts')
   
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table').DataTable();

            let deleteID;
            $('body').on('click', '#deleteUnit', function(){
                deleteID = $(this).data('id');

                if(confirm('Are you sure want to delete?')){
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: 'units/'+deleteID,
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