<!-- Payment Method Id Field -->
<div class="form-group">
    {!! Form::label('payment_method_id', 'Payment Method Id:') !!}
    <p>{!! $payment->payment_method_id !!}</p>
</div>

<!-- Order Id Field -->
<div class="form-group">
    {!! Form::label('order_id', 'Order Id:') !!}
    <p>{!! $payment->order_id !!}</p>
</div>

<!-- Amount Field -->
<div class="form-group">
    {!! Form::label('amount', 'Amount:') !!}
    <p>{!! $payment->amount !!}</p>
</div>

<!-- Memo Field -->
<div class="form-group">
    {!! Form::label('memo', 'Memo:') !!}
    <p>{!! $payment->memo !!}</p>
</div>

