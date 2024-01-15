<div class="table-responsive">
    <table class="table table-bordered" id="districts-table">
        <thead>
            <tr>
            <th>ID</th>
                <th>Iso Code</th>
        <th>District Name</th>
        <th>City</th>
        <th>Lat</th>
        <th>Lng</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        {{--}}
        <tbody>
        @foreach($districts as $district)
            <tr>
                <td>{!! $district->iso_code !!}</td>
            <td>{!! $district->city_province_id !!}</td>
            <td>{!! $district->default_name !!}</td>
            <td>{!! $district->slug !!}</td>
            <td>{!! $district->lat !!}</td>
            <td>{!! $district->lng !!}</td>
            <td>{!! $district->order !!}</td>
            <td>{!! $district->is_active !!}</td>
                <td>
                    {!! Form::open(['route' => ['districts.destroy', $district->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('districts.show', [$district->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('districts.edit', [$district->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
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
               $('#districts-table').DataTable({
               processing: true,
               serverSide: true,
                bAutoWidth:false,
               ajax: '{{ url('districts') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'iso_code', name: 'iso_code' },
                        { data: 'default_name', name: 'default_name' },
                        { data: 'city', name: 'city' },
                        { data: 'lat', name: 'lat' },
                        { data: 'lng', name: 'lng' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });

            let deleteID;
            $('body').on('click', '#deleteDistrict', function(){
                deleteID = $(this).data('id');

                if(confirm('Are you sure want to delete?')){
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: 'district/'+deleteID,
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
