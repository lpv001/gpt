<!-- Order Id Field -->
<div class="col-sm-6">
    {!! Form::label('order_id_label', 'Order ID:') !!}
    {!! Form::number('order_id', @$order->id, ['class' => 'form-control', 'disabled']) !!}                                        
</div>

<!-- User Id Field -->
<div class="col-sm-6">
    {!! Form::label('user_id', 'Ordered By:') !!}
    {!! Form::select('user_id_disable', @$edit? $users  : [], null, ['class' => 'form-control' , 'required', 'disabled']) !!}
    {!! Form::hidden('user_id', $order->user_id) !!}
</div>

<!-- Date Order Field -->
<div class="col-sm-6">
    {!! Form::label('date_order_placed', 'Date Ordered:') !!}
    {!! Form::hidden('date_order_placed', $order->created_at) !!}
    {!! Form::text('date_order_placed_txt', $order->created_at, ['class' => 'form-control' , 'disabled']) !!}
</div>

<!-- Shop Id Field -->
<div class="col-sm-6">
    {!! Form::label('shop_id', 'Shop Name:') !!}
    {!! Form::select('shop_id', @$edit? $shops  : [], @$order->shop_id, ['placeholder' => 'Please Select Shop','class' => 'form-control', 'required']) !!}
</div>

<!-- delivery_option_id  Field -->
<div class="col-sm-6">
    {!! Form::label('delivery_option_id', 'Delivery Method:') !!}
    {!! Form::select('delivery_option_id', @$edit? $deliveryProvider  : [], null, ['class' => 'form-control' , 'required']) !!}
</div>

<!-- delivery address Field -->
<div class="col-sm-6">
    {!! Form::label('address_full_name', 'Address:') !!}
    {!! Form::text('address_full_name', $deliveries['addresses']['tag'] . ':' . $deliveries['addresses']['address'], ['class' => 'form-control' , 'disabled']) !!}
</div>

<!-- address_phone Id Field -->
<div class="col-sm-6">
    {!! Form::label('address_phone', 'Contact Phone:') !!}
    {!! Form::text('address_phone',  $deliveries['addresses']['phone'], ['class' => 'form-control' , 'disabled']) !!}
</div>

<!-- payment_method_id Field -->
<div class="col-sm-6">
    {!! Form::label('payment_method_id', 'Payment Method:') !!}
    {!! Form::select('payment_method_id', @$edit? $payment_methods : [], null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Payment detail -->
@if (count($payments) > 0)
<div class="col-sm-6">
    {!! Form::label('address_full_name', 'payment Detail:') !!}
    <div>Paid to:
      <ul>
      <li>Provider: {{ $payments['provider_name'] }}</li>
      <li>Account Name: {{ $payments['payee_account_name'] }}</li>
      <li>Account Number: {{ $payments['payee_account_number'] }}</li>
      <li>phone Number: {{ $payments['payee_phone_number'] }}</li>
      </ul>
    </div>
    <div>Paid by:
      <ul>
      <li>Account Name: {{ $payments['payer_account_name'] }}</li>
      <li>Account Number: {{ $payments['payer_account_number'] }}</li>
      <li>phone Number: {{ $payments['payer_phone_number'] }}</li>
      <li>Payment Code: {{ $payments['payment_code'] }}</li>
      <!-- <li>Amount: ${{ $payments['amount'] }}</li> -->
      </ul>
    </div>
</div>
@endif

<!-- Action Field -->
<div class="col-sm-6">
    <div>
    {!! Form::label('order_action', 'Action:') !!}
    {!! Form::select('order_status_id', @$order_status, @$order->order_status_id, ['placeholder' => 'Select Order Action','class' => 'form-control', 'required']) !!}
    </div>
    <!-- Submit Field -->
    <div style="margin: auto;">
        {!! Form::submit('Save', ['class' => 'btn btn-primary', 'id'=>'btn-save']) !!}
        <a href="{!! route('orders.index') !!}" class="btn btn-default">Cancel</a>
    </div>
</div>


<hr>

<!-- Order items -->
<div class="form-group col-sm-12">
<table class="table">
  <h2>Ordered Items</h2>
  <thead>
    <tr>
      <th scope="col">Product ID</th>
      <th scope="col">Product</th>
      <th scope="col">Quantity</th>
      <th scope="col">Unit Price</th>
      <th scope="col">Amount</th>
    </tr>
  </thead>
  <tbody id="data-items">
    @foreach (@$items as $key => $item)
    <tr>
      <td id="item-id">{{ $item->product_id }}</td>
      <td id="product_name">{{ $item->name }}</td>
      <td id="quantity">{{ $item->quantity }}</td>
      <td id="unit_price">${{ $item->unit_price }}</td>
      <td id="amount">${{ $item->unit_price * $item->quantity }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>

<div class="row">
</div>

<!-- Total Field -->
<div class="pull-right" style="padding: 0 5em 0 0;">
    <!-- Sub Total -->
    <p>{!! Form::label('total', 'Sub Total :') !!}$ <span id="total">{!! $order->sub_total !!}</span></p>
    
    <!-- VAT -->
    <p>{!! Form::label('vat', 'VAT :') !!}$ <span id="total">0</span>
    
    <!-- Delivery Fee -->
    <p>{!! Form::label('delivery_fee', 'Delivery Fee :') !!}<span id="total">$ {{ number_format(@$deliveryOption['delivery_fee'] ?? 0, 2) }}</span>
    
    <!-- Discounts -->
    @if (count(@$discounts['items']) > 0)
    <div>
      <p>{!! Form::label('discounts', 'Discounts :') !!}</p>
      <ul>
      @foreach ($discounts['items'] as $discount_item)
        <li>{{ $discount_item['name'] }}<span>(-${{number_format($discount_item['value'], 2) }})</li>
      @endforeach
      </ul>
    </div>
    @endif
    
    <!-- Order Total -->
    @php
    $discount_total = isset($discounts['total_discount'])? $discounts['total_discount'] : 0;
    @endphp
    <p>{!! Form::label('total', 'Order Total :') !!}<span id="total">$ {{ (($order->sub_total + $deliveryOption['delivery_fee']) - $discount_total) }}</span>
</div>
