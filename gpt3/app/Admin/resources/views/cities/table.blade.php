<div class="table-responsive">
    <table class="table table-bordered" id="cities-table">
        <thead>
            <tr>
            <th>ID</th>
                <th>ISO Code</th>
        <th>City Name</th>
        <!-- <th>Slug</th> -->
        <th>Lat</th>
        <th>Lng</th>
        <th>Country</th>
        <!-- <th>Order</th> -->
        <!-- <th>Is Active</th> -->
                <th colspan="3">Action</th>
            </tr>
        </thead>
        {{--}}
        <tbody>
        @foreach($cities as $city)
            <tr>
                <td>{!! $city->iso_code !!}</td>
            <td>{!! $city->default_name !!}</td>
            <td>{!! $city->slug !!}</td>
            <td>{!! $city->lat !!}</td>
            <td>{!! $city->lng !!}</td>
            <td>{!! $city->is_city !!}</td>
            <td>{!! $city->order !!}</td>
            <td>{!! $city->is_active !!}</td>
            <td>{!! $city->country_id !!}</td>
                <td>
                    {!! Form::open(['route' => ['cities.destroy', $city->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('cities.show', [$city->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('cities.edit', [$city->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
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
               $('#cities-table').DataTable({
               processing: true,
               serverSide: false,
                bAutoWidth:false,
               ajax: '{{ url('cities') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'iso_code', name: 'iso_code' },
                        { data: 'default_name', name: 'default_name' },
                        { data: 'lat', name: 'lat' },
                        { data: 'lng', name: 'lng' },
                        { data: 'country', name: 'country' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });

            let deleteID;
            $('body').on('click', '#deleteCity', function(){
                deleteID = $(this).data('id');

                if(confirm('Are you sure want to delete?')){
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: 'city/'+deleteID,
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
