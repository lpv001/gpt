@extends('frontend::layouts.main')

@section('content')
<br>
<section class="section-request padding-y-sm">
<div class="container">

{{--}}
<header class="section-heading heading-line1">
	<h4 class="title-section bg1 text-uppercase">Recommended items</h4>
</header>
--}}

<div class="row">
	<aside class="col-sm-3 left-side mb-4">
	 	@include('frontend::my.partials.sidebar_menu')
	</aside> <!-- col.// -->


<main class="col-sm-9 right-side">
<div class="row">

<div class="col-md-12">


	<div class="card">
		<div class="pull-left">
			<header class="card-header">
				<div class="row">
					<div class="col-sm">
						<h6 class="title text-left">{{__('frontend.order_detail')}}</h6>
					</div>
				</div>
				
			</header>
		</div>

		{{-- {{dd($order)}} --}}
  	<div class="card-body px-0">
		<div class="row mx-2">
			<div class="col-sm">
				<h5 class="font-weight-bold">Order Information</h5>
				<div class="row">
					<div class="col-sm">
						<p class="m-0">Status</p>
						<p class="m-0">Order ID</p>
						<p class="m-0">Order By</p>
						<p class="m-0">Order date</p>
						<p class="m-0 text-danger">Note</p>
					</div>
					<div class="col-sm">
						@if (count(@$order) > 0)
							<p class="m-0 text-capitalize">: {{ $status[$order['order_status_id']] }}</p>
							<p class="m-0">: {{ $order['id'] }}</p>
							<p class="m-0">: {{ @$user_order['full_name'] ?? '' }}</p>
							<p class="m-0">: {{ $order['date_order_placed'] }} </p>
							<p class="m-0 text-danger">: {{ $order['note'] }}</p>
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
												<p class="mb-0">$ {{ $item['unit_price'] }}</p>
												<p class="mb-0">x {{ $item['quantity'] }} <span class="ml-5">$ {{ number_format($item['unit_price'] * $item['quantity'] , 2) }}</span></p>
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
			<div class="col-sm">
				{{-- <h5 class="font-weight-bold">Order Information</h5> --}}
				<div class="row">
					<div class="col-sm">
						<p class="m-0">{{ __('frontend.sub_total') }}</p>
						<p class="m-0">{{ __('frontend.discount') }}</p>
						<p class="m-0">{{ __('frontend.delivery_fee') }}</p>
					</div>
					<div class="col-sm">
						@if (count(@$order) > 0)
							<p class="m-0 text-uppercase">: $ {{ $order['total'] }}</p>
							<p class="m-0">: {{  number_format($discount, 2) }}</p>
							<p class="m-0">: {{ number_format(@$deliveryOption['delivery_fee'] ?? 0, 2) }}</p>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm"></div>
		</div>
		{{-- <hr> --}}
		<div class="row mx-2 mt-2">
			<div class="col-sm">
				<div class="row">
					<div class="col-sm">
						<h5 class="font-weight-bold">Total</h5>
					</div>
					<div class="col-sm">
						<h5 class="font-weight-bold">$ {{ count(@$order) > 0 ? number_format(@$order['total'],2) : '' }}</h5>
					</div>
				</div>
			</div>
			<div class="col-sm"></div>
		</div>
		<hr>

		<div class="row mx-2">
			<div class="col-sm">
				<h5 class="font-weight-bold">Delivery Method</h5>
				<div class="row">
					<div class="col-sm">
						{{-- <p class="m-0">{{ __('frontend.name') }}</p> --}}
						{{-- <p class="m-0">{{ __('frontend.phone') }}</p> --}}
						{{-- <p class="m-0">{{ __('frontend.address') }}</p> --}}
						<p class="m-0">{{ __('frontend.delivery_by') }}</p>
					</div>
					<div class="col-sm">
						@if (count(@$deliveryOption) > 0)
							{{-- <p class="m-0 text-uppercase">: $ {{ 0 }}</p> --}}
							{{-- <p class="m-0">: {{  number_format($discount, 2) }}</p> --}}
							<p class="m-0">: {{ @$deliveryOption['delivery_name'] }}</p>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm"></div>
		</div>

		<hr>
		<div class="row mx-2">
			<div class="col-sm">
				<h5 class="font-weight-bold">Payment Method</h5>
				<div class="row">
					<div class="col-sm">
						<p class="m-0">{{ __('frontend.cash_on_delivery') }}</p>
					</div>
				</div>
			</div>
			<div class="col-sm"></div>
		</div>

		{{-- <hr> --}}
		<div class="row mx-2 mt-4">
			<div class="col-sm">
				<h5 class="font-weight-bold">{{ __('frontend.action') }}</h5>
				<div class="row">
					<div class="col-sm">
						@switch(@$order['order_status_id'])
							@case(0)
								<a href="{{ route('customer-order-update') }}?order_id={{@$order['id']}}&&order_status_id=1" class="btn btn-sm btn-success">Accept Order</a>
								<a href="{{ route('customer-order-update') }}?order_id={{@$order['id']}}&&order_status_id=0" class="btn btn-sm btn-danger">Reject Order</a>
								@break
							@default
							<a href="{{ route('customer-order-update') }}?order_id={{@$order['id']}}&&order_status_id=2" class="btn btn-sm btn-warning">Mark as  Delivery</a>

						@endswitch
						{{-- @if (@$order['order_status_id'])
							
						@endif --}}
						{{-- <a href="" class="btn btn-sm btn-warning">Mark as Delivery</a>
						<a href="" class="btn btn-sm btn-danger">Cancel Ordered</a> --}}
					</div>
				</div>
			</div>
			<div class="col-sm"></div>
		</div>

	</div>
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
