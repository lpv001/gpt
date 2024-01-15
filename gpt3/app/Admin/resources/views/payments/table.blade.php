<div class="table-responsive">
    <table class="table table-bordered" id="payments-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Payment Method</th>
                <th>Order</th>
                <th>Amount</th>
                <th>Memo</th>
                <th>Created At</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        {{--}}
        <tbody>
        @foreach($payments as $payment)
            <tr>
                <td>{!! $payment->payment_method_id !!}</td>
            <td>{!! $payment->order_id !!}</td>
            <td>{!! $payment->amount !!}</td>
            <td>{!! $payment->memo !!}</td>
                <td>
                    {!! Form::open(['route' => ['payments.destroy', $payment->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('payments.show', [$payment->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('payments.edit', [$payment->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
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
               $('#payments-table').DataTable({
               processing: true,
            //    serverSide: true,
                bAutoWidth:false,
               ajax: '{{ url('payments') }}',
               columns: [
                        { data: 'id', name: 'id' },
                        { data: 'payment_method_id', name: 'payment_method_id' },
                        { data: 'order_id', name: 'order_id' },
                        { data: 'amount', name: 'amount' },
                        { data: 'memo', name: 'memo' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false },
        
                     ]
            });

            let deleteID;
            $('body').on('click', '#deletePayments', function(){
                deleteID = $(this).data('id');

                if(confirm('Are you sure want to delete?')){
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: 'payments/'+deleteID,
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