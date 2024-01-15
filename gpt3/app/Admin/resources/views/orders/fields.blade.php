<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Shop Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shop_id', 'Shop Id:') !!}
    {!! Form::number('shop_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Order Placed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_order_placed', 'Date Order Placed:') !!}
    {!! Form::date('date_order_placed', null, ['class' => 'form-control','id'=>'date_order_placed']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#date_order_placed').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Date Order Paid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_order_paid', 'Date Order Paid:') !!}
    {!! Form::date('date_order_paid', null, ['class' => 'form-control','id'=>'date_order_paid']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#date_order_paid').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Order Status Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order_status_id', 'Order Status Id:') !!}
    {!! Form::number('order_status_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Delivery Option Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('delivery_option_id', 'Delivery Option Id:') !!}
    {!! Form::number('delivery_option_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Full Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_full_name', 'Address Full Name:') !!}
    {!! Form::text('address_full_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_email', 'Address Email:') !!}
    {!! Form::text('address_email', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_phone', 'Address Phone:') !!}
    {!! Form::text('address_phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Street Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_street_address', 'Address Street Address:') !!}
    {!! Form::text('address_street_address', null, ['class' => 'form-control']) !!}
</div>

<!-- Address City Province Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_city_province_id', 'Address City Province Id:') !!}
    {!! Form::number('address_city_province_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Address District Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_district_id', 'Address District Id:') !!}
    {!! Form::number('address_district_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Pickup Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone_pickup', 'Phone Pickup:') !!}
    {!! Form::text('phone_pickup', null, ['class' => 'form-control']) !!}
</div>

<!-- Note Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('note', 'Note:') !!}
    {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
</div>

<!-- Preferred Delivery Pickup Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('preferred_delivery_pickup_date', 'Preferred Delivery Pickup Date:') !!}
    {!! Form::date('preferred_delivery_pickup_date', null, ['class' => 'form-control','id'=>'preferred_delivery_pickup_date']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#preferred_delivery_pickup_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Preferred Delivery Pickup Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('preferred_delivery_pickup_time', 'Preferred Delivery Pickup Time:') !!}
    {!! Form::text('preferred_delivery_pickup_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Payment Method Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_method_id', 'Payment Method Id:') !!}
    {!! Form::number('payment_method_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Delivery Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('delivery_id', 'Delivery Id:') !!}
    {!! Form::number('delivery_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Delivery Pickup Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('delivery_pickup_date', 'Delivery Pickup Date:') !!}
    {!! Form::date('delivery_pickup_date', null, ['class' => 'form-control','id'=>'delivery_pickup_date']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#delivery_pickup_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Pickup Lat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pickup_lat', 'Pickup Lat:') !!}
    {!! Form::number('pickup_lat', null, ['class' => 'form-control']) !!}
</div>

<!-- Pickup Lon Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pickup_lon', 'Pickup Lon:') !!}
    {!! Form::number('pickup_lon', null, ['class' => 'form-control']) !!}
</div>

<!-- Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('total', 'Total:') !!}
    {!! Form::number('total', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('orders.index') !!}" class="btn btn-default">Cancel</a>
</div>
