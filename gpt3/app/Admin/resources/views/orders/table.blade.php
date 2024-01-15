<div class="table-responsive">
    <table class="table table-bordered" id="orders-table">
        <thead>
            <tr>
              <th>Order ID</th>
              <th>From Shop</th>
                <th>Ordered By</th>
                <th>Ordered Date</th>
                <th>Total</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        {{--}}
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{!! $order->id !!}</td>
            <td>{!! @\App\Admin\Models\Shop::find($order->shop_id)->name !!}</td>
            <td>{!! @\App\Admin\Models\User::find($order->user_id)->full_name !!}</td>
            <td>{!! $order->date_order_placed !!}</td>
            <td>{!! $order->total !!} $</td>
            @if($order->order_status_id == 0)
                <td>Initiate</td>
            @elseif($order->order_status_id == 1)
                <td>Pending</td>
            @elseif($order->order_status_id == 2)
                <td>Dilivery</td>
            @elseif($order->order_status_id == 3)
                <td>Completed</td>
            @else
                <td>Cancelled</td>
            @endif

                <td>
                    {!! Form::open(['route' => ['orders.destroy', $order->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('orders.show', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('orders.edit', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
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
               $('#orders-table').DataTable({
               processing: true,
               serverSide: true,
                bAutoWidth:false,
               ajax: '{{ url('orders') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'shop', name: 'shop' },
                        { data: 'buyer', name: 'buyer' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'total', name: 'total' },
                        { data: 'status', name: 'status' },
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
                        url: 'orders/'+deleteID,
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
