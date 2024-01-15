<div class="row">
    <div class="col-xs-6 col-sm-5 col-md-4">
      <div>
        <!-- Order ID -->
        <div class="col-sm-12">
        <p><span>{!! Form::label('id', 'Order ID : ') !!}</span>
        {!! $order->id !!} ({!!$order_status[$order->order_status_id]!!})</p>
        </div>
        
        <!-- Date Ordered -->
        <div class="col-sm-12">
            <p><span>{!! Form::label('id', 'Date Ordered :') !!} </span>
                {!! $order->created_at !!}
            </p>
        </div>
        
        <!-- User -->
        <div class="col-sm-12">
            <p><span>{!! Form::label('id', 'Ordered By : ') !!} </span>
                {!! @\App\Admin\Models\User::find($order->user_id)->full_name !!}
            </p>
        </div>
        
        <!-- address_phone Id Field -->
        <div class="col-sm-12">
            <p><span>{!! Form::label('id', 'Contact Phone :') !!}&nbsp;</span>
                {!! $order->address_phone !!}
            </p>
        </div>
        
        <!-- R3: delivery address Field -->
        <div class="col-sm-12">
          <p><span>{!! Form::label('id', 'Contact Email :') !!}&nbsp;</span>
              {!! $order->address_email !!}
          </p>
        </div>
        
        <!-- Shop Id Field -->
        <div class="col-sm-12">
          <p><span>{!! Form::label('id', 'Ordered From Shop : ') !!}&nbsp;</span>
              {!! @\App\Admin\Models\Shop::find($order->shop_id)->name !!}
          </p>
        </div>
        
      </div>
    </div>
    
    <div class="col-xs-6 col-sm-6 col-md-4">
      <div>
        <!-- delivery_option_id  Field -->
        <div class="col-sm-12">
            <p><span>{!! Form::label('id', 'Delivery Method :') !!}&nbsp;</span>
                {!! $deliveryProvider[$order->delivery_option_id] !!}
            </p>
        </div>
      </div>
      
      <!-- delivery address Field -->
      <div class="col-sm-12">
        <p><span>
          {!! Form::label('address_full_name', 'Address:') !!}</span>
          {!! $deliveries['addresses']['tag'] . ':' . $deliveries['addresses']['address'] !!}
        </p>
      </div>
    </div>
    
    {{-- Payments --}}
    <div class="col-xs-6 col-sm-6 col-md-4">
      <div>
        <div class="col-sm-12">
          <p><span>{!! Form::label('payment_method_id', 'Payment Method :') !!}&nbsp;</span>
              {!! $payment_methods[$order->payment_method_id] !!}
          </p>
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
        
      </div>
    </div>
</div> {{-- row.//--}}

<div class="row">
  <div class="col-sm-12 d-flex justify-content-center">
      <a href="{!! route('orders.edit', [$order->id]) !!}" class="btn btn-primary">Edit Order</a>
  </div>
</div>

{{-- Ordered Items --}}
<table class="table">
  <h2>Ordered Items</h2>
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Product</th>
      <th scope="col">Quantity</th>
      <th scope="col">Unit Price</th>
      <th scope="col">Amount</th>
    </tr>
  </thead>
  <tbody>
  @foreach($items as $item)
    <tr>
      <td id="item-id">{{ $item->product_id }}</td>
      <td id="product_name">{{ $item->name }}</td>
      <td id="quantity">{{ $item->quantity }}</td>
      <td id="unit_price">$ {{ $item->unit_price }}</td>
      <td id="amount">$ {{ $item->unit_price * $item->quantity }}</td>
    </tr>
  @endforeach
  </tbody>
  
  <tfoot>
    <tr>
      <td colspan="4" style="text-align:right; font-weight:bold">Sub Total :</td>
      <td>$ {!! $order->sub_total !!}</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:right; font-weight:bold">VAT :</td>
      <td>$ 0</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:right; font-weight:bold">Delivery Fee :</td>
      <td>$ {{ number_format(@$deliveryOption['delivery_fee'] ?? 0, 2) }}</td>
    </tr>
    @if (count(@$discounts['items']) > 0)
    @foreach ($discounts['items'] as $discount_item)
    <tr>
      <td colspan="4" style="text-align:right; font-weight:bold">{{ $discount_item['name'] }} :</td>
      <td>(-${{number_format($discount_item['value'], 2) }})</td>
    </tr>
    @endforeach
    @endif
    
    @php
    $discount_total = isset($discounts['total_discount'])? $discounts['total_discount'] : 0;
    @endphp
    <tr>
      <td colspan="4" style="text-align:right; font-weight:bold">Order Total :</td>
      <td style="font-weight:bold">$ {{ (($order->sub_total + $deliveryOption['delivery_fee']) - $discount_total) }}</td>
    </tr>
  </tfoot>
</table>

{{-- Back button --}}
<div class="col-sm-12">
    <a href="{!! route('orders.index') !!}" class="btn btn-default">Back</a>
</div>
