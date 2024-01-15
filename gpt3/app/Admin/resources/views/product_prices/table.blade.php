<div class="table-responsive">

    <table class="table table-bordered"  id="productPrices-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                {{-- <th>Product Type</th> --}}
                <th>Unit</th>
                <th>City</th>
                <th>Distributor</th>
                <th>Wholsaler</th>
                <th>Retailer</th>
                <th>Buyer</th>
                <th>Point</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (@$productPrices)
                @foreach($productPrices as $key => $productPrice)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $productPrice->product->name ?? '' }}</td>
                        {{-- <td>{{ $productPrice->type_id }}</td> --}}
                        <td>{{ $productPrice->unit->name ?? '' }}</td>
                        <td>{{ $productPrice->city->default_name ?? 'All' }}</td>
                        <td>{!! number_format($productPrice->distributor, 2) !!}</td>
                        <td>{!! number_format($productPrice->wholesaler, 2) !!}</td>
                        <td>{!! number_format($productPrice->retail, 2) !!}</td>
                        <td>{!! number_format($productPrice->buyer, 2) !!}</td>
                        <td>{!! number_format($productPrice->flag, 2) !!}</td>
                            <td>
                                {!! Form::open(['route' => ['productPrices.destroy', $productPrice->id], 'method' => 'delete']) !!}
                                <div class='btn-group'>
                                    {{-- <a href="{!! route('productPrices.show', [$productPrice->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                                    <a href="{!! route('productPrices.edit', [$productPrice->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
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
            $('#productPrices-table').DataTable();

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