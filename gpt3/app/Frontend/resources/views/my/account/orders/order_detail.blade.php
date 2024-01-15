@extends('frontend::layouts.main')

@section('content')
<br>
<section class="section-request padding-y-sm">
<div class="container">

<div class="row">
	<aside class="col-sm-3 left-side mb-4">
	 	@include('frontend::my.partials.sidebar_menu')
	</aside> <!-- col.// -->

<main class="col-sm-9 right-side">
<div class="row">

<div class="col-md-12">

	<div class="card">
		<div class="pull-left">
			<header class="card-header bg-white">
				<div class="row">
					<div class="col-sm">
						<h4 class="title text-left bg-white p-4">{{__('frontend.order_detail')}}</h4>
					</div>
				</div>
				
			</header>
		</div>

	@if(Session::has('message'))
		<div class="py-5 text-center">
			<h4 class="text-secondary">{{ __('frontend.msg_internal_errors') }}</h4>
		<p class="text-secondary">{{ Session::get('message') }}</p>

		</div>
	@else
		<div class="card-body px-0">
			<div class="row mx-2">
				<div class="col-sm">
					<h5 class="font-weight-bold">{{ __('frontend.order_information') }}</h5>
					<div class="row">
						<div class="col-sm">
							<p class="m-0">{{ __('frontend.order_status') }}</p>
							<p class="m-0">{{ __('frontend.order_id') }}</p>
							<p class="m-0">{{ __('frontend.order_by') }}</p>
							<p class="m-0">{{ __('frontend.order_date') }}</p>
							<!-- <p class="m-0 text-danger">{{ __('frontend.note') }}</p> -->
						</div>
						<div class="col-sm">
							@if (count(@$order) > 0)
								<p class="m-0 text-capitalize">: {{ $status[$order['order_status_id']] }}</p>
								<p class="m-0">: {{ $order['id'] }}</p>
								<p class="m-0">: {{ @$user_order['full_name'] ?? '' }}</p>
								<p class="m-0">: {{ $order['created_at'] }} </p>
								<!-- <p class="m-0 text-danger">: {{ $order['note'] }}</p> -->
							@endif
						</div>
					</div>
				</div>
				<div class="col-sm"></div>
			</div>
			<hr>

			@if (count(@$order_detail))
				@foreach ($order_detail as $item)
					<div class="row mx-2">
						<div class="row mx-2" style="width: 98%;">
							<div class="col-sm">
								<div class="row">
									<div class="col-sm p-2">
										<div class="row">
											<div class="col-sm d-flex flex-row">
												{{-- <i class="fa fa-shopping-cart" aria-hidden="true" style="font-size: 35px"></i> --}}
												<img 
													src="{{ asset($item['image_name']) }}" 
													alt="img" 	
													width="10%" 
													class="img-thumbnail mr-3"
													style="border: none">
												<div class="">
													<p class="mb-0">{{ $item['name'] }}</p>
													@if ($item['option_name'] != null)
													    <p class="mb-0">{{ $item['option_name'] }}: <span class="ml-5">{{ $item['option_value_name'] }}</span></p>
													@endif
													<p class="mb-0">$ {{ $item['unit_price'] }} x {{ $item['quantity'] }} = <span class="ml-5">$ {{ number_format(floatval(floatval($item['unit_price']) * intval($item['quantity'])) , 2) }}</span></p>
												</div>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				@endforeach
			@endif
			
			<hr>

			<div class="row mx-2">
				<div class="col">
					{{-- total --}}
					<div class="row">
						<div class="col-3">
							<p class="m-0">{{ __('frontend.item_total') }}</p>
						</div>
						<div class="col-9">
							<p class="m-0">: ${{ $order['sub_total'] }}</p>
						</div>
					</div>
					
					{{-- Delivery fee  --}}
					<div class="row">
						<div class="col-3">
							<p class="m-0">{{ __('frontend.delivery_fee') }}</p>
						</div>
						<div class="col-9">
							<p class="m-0">: ${{ number_format(@$deliveryOption['delivery_fee'] ?? 0, 2) }}</p>
						</div>
					</div>
					
					<div class="row">
						<div class="col-3">
							<p class="m-0">{{ __('frontend.total') }}</p>
						</div>
						<div class="col-9">
							<p class="m-0">: ${{ ($order['sub_total'] + $deliveryOption['delivery_fee']) }}</p>
						</div>
					</div>
					
					{{-- Discount --}}
					@php
					$ic = 0;
					$discount_total = 0;
					@endphp
					@if (count(@$discount['items']) > 0)
					@foreach ($discount['items'] as $discount_item)
					<div class="row">
						@if ($ic == 0)
						<div class="col-3">
							<p class="m-0">{{ __('frontend.discount') }}</p>
						</div>
						<div class="col-9">
							<p class="m-0">:<span class="">{{ $discount_item['name'] }}<span>(-${{number_format($discount_item['value'], 2) }})</span></span></p>
						</div>
						@php
						$ic = $ic +1;
						@endphp
						@else
						<div class="col-3">
						</div>
						<div class="col-9">
							<p class="m-0">:<span class="">{{ $discount_item['name'] }}<span>(-${{number_format($discount_item['value'], 2) }}$)</span></span></p>
						</div>
						@endif
					</div>
					@php
					$discount_total += $discount_item['value'];
					@endphp
					@endforeach
					@endif
					
					<div class="row">
						<div class="col-3">
							<h4 class="font-weight-bold">{{ __('frontend.order_total') }}</h4>
						</div>
						<div class="col-9">
							<h4 class="font-weight-bold">: ${{ (($order['sub_total'] + $deliveryOption['delivery_fee']) - $discount_total) }}</h4>
						</div>
					</div>
				</div>
			</div>
			<hr>

			<div class="row mx-2">
				<div class="col">
					<h5 class="font-weight-bold">{{ __('frontend.delivery_information') }}</h5>
					<div class="row">
						<div class="col-3">
							<p class="m-0">{{ __('frontend.delivery_by') }}</p>
						</div>
						<div class="col-9">
							@if (count(@$deliveryOption) > 0)
								<p class="m-0">: {{ @$deliveryOption['delivery_name'] }}</p>
							@endif
						</div>
					</div>
					@if (count(@$deliveries) > 0)
					<div class="row">
						<div class="col-3">
							<p class="m-0">{{ __('frontend.delivery_to') }}</p>
						</div>
						<div class="col-9">: 
							{{ @$user_order['full_name'] ?? '' }}
						</div>
					</div>
					<div class="row">
						<div class="col-12">
								<p class="m-0">Address: {{ @$deliveries['addresses']['address'] }}</p>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
								<p class="m-0">Phone: {{ @$deliveries['addresses']['phone'] }}</p>
						</div>
					</div>
					@endif
				</div>
			</div>

			<hr>
			<div class="row mx-2">
				<div class="col">
					<h5 class="font-weight-bold">{{ __('frontend.payment_information') }}</h5>

					<div class="row">
						<div class="col-3">
							<p class="m-0">{{ __('frontend.payment_method') }}</p>
						</div>
						<div class="col-9">: 
						@if ($order['payment_method_id'] == 1)
							{{ __('frontend.cash_on_delivery') }}
						@elseif ( count($payments) > 0)
							{{ @$payments['payment_method_name'] }}
              <div>{{ __('frontend.paid_to') }}:
              <ul>
              <li>{{ __('frontend.bank_provider') }}: {{ $payments['provider_name'] }}</li>
              <li>{{ __('frontend.account_name') }}: {{ $payments['payee_account_name'] }}</li>
              <li>{{ __('frontend.account_number') }}: {{ $payments['payee_account_number'] }}</li>
              <li>{{ __('frontend.phone_number') }}: {{ $payments['payee_phone_number'] }}</li>
              </ul>
              </div>
              <div>{{ __('frontend.paid_by') }}:
              <ul>
              <li>{{ __('frontend.account_name') }}: {{ $payments['payer_account_name'] }}</li>
              <li>{{ __('frontend.account_number') }}: {{ $payments['payer_account_number'] }}</li>
              <li>{{ __('frontend.phone_number') }}: {{ $payments['payer_phone_number'] }}</li>
              <li>{{ __('frontend.payment_code') }}: {{ $payments['payment_code'] }}</li>
              <!--<li>Amount: ${{ $payments['amount'] }}</li>-->
              </ul>
  						</div>
						@endif
						</div>
					</div>
				</div>
			</div>

		</div>
	@endif
</div>

</div> <!-- col // -->
</div></main>

</div>
</div> <!-- row.// -->
</div><!-- container // -->
</section>

@endsection

@section('scripts')
	<script>
	</script>
@endsection
