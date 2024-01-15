<div class="table-responsive">
    <table class="table table-bordered" id="categories-table">
        <thead>
            <tr>
              <th>ID</th>
                <th>Parent Id</th>

        <th>Default Name</th>
        <th>Slug</th>
        <th>Order</th>
        <th>Image</th>
        <th>Is Active</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        {{--}}
        <tbody>
        @foreach($categories as $category)
            <tr>
              <td>{!! $category->id !!}</td>
                <td>{!! $category->parent_id !!}</td>

            <td>{!! $category->default_name !!}</td>
            <td>{!! $category->slug !!}</td>
            <td>{!! $category->order !!}</td>
            <td>
              @if($category->image_name != "")
              <img src="{{ asset('uploads/images/categories/'.$category->image_name)}}" height="70px" />
              @endif

            </td>
            <td>{!! $category->is_active !!}</td>
                <td>
                    {!! Form::open(['route' => ['categories.destroy', $category->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('categories.show', [$category->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('categories.edit', [$category->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
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
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
               $('#categories-table').DataTable({
               processing: true,
            //    serverSide: true,
                bAutoWidth:false,
               ajax: '{{ url('') }}',
               columns: [
                        // { data: 'id', name: 'id' },
                        // { data: 'product', name: 'product' },
                        // { data: 'product_type', name: 'product_type' },
                        // { data: 'unit', name: 'unit' },
                        // { data: 'city', name: 'city' },
                        // { data: 'unit_price', name: 'unit_price' },
                        // { data: 'sale_price', name: 'sale_price' },
                        // { data: 'created_at', name: 'created_at' },
                        // { data: 'action', name: 'action', orderable: false },
        
                     ]
            });

            // let deleteID;
            // $('body').on('click', '#deleteproductPrice', function(){
            //     deleteID = $(this).data('id');

            //     if(confirm('Are you sure want to delete?')){
            //         $.ajaxSetup({
            //             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //         });
            //         $.ajax({
            //             url: 'productPrices/'+deleteID,
            //             method: 'POST',
            //             success: function(result) {
            //                 window.location.href = 'productPrices';
            //             },
            //             error: function (error) {
            //                 alert('error; ' + eval(error));
            //             }
            //         });
            //     }
            // });
        });
    </script>
@endsection