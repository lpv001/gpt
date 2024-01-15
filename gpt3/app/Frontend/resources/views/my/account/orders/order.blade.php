@extends('frontend::layouts.main')

@section('content')

<style>
	.rounded-text {
		border-radius: 100px;
	}
	/* .btn-outline-warning {
		border:1px solid #fd7e14 !important;
	} */
	.btn-outline-warning:hover {
		color: #fff;
	}
</style>

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
						
						<div class="p-4">
							<h5 class="title font-weight-bold">{{ __('frontend.my_orders') }}</h5>
							<!--
							<div class="d-flex mt-4">
								<a href="#" class="px-3 py-1 btn btn-outline-warning border rounded-text mr-2">{{ __('frontend.all_orders') }}</a>
								<a href="#" class="px-3 py-1 btn btn-outline-warning border rounded-text mr-2">{{ __('frontend.pending_orders') }}</a>
								<a href="#" class="px-3 py-1 btn btn-outline-warning border rounded-text mr-2">{{ __('frontend.delivered_orders') }}</a>
								<a href="#" class="px-3 py-1 btn btn-outline-warning border rounded-text mr-2">{{ __('frontend.completed_orders') }}</a>
							</div>
							-->
						</div>

						<div class="card-body pt-0">
							@if (count(@$orders) > 0)
								@foreach (@$orders as $order)
									<div class="list-group mb-3">
										<a  class="list-group-item list-group-item-action " href="{{ route('orders.show',$order['id']) }}">
											<figure class="media">
												<div class="img-wrap pull-left"><img src="https://static.thenounproject.com/png/832921-200.png" class="img-thumbnail img-sm"></div>
												<figcaption class="media-body">
													<div class="">
														{{-- <div class="price-wrap pull-right">
															@if ($order->order_status_id == 0)
																<span class="badge badge-pill badge-warning py-2 text-white">{{__('frontend.processing')}}</span>
															@elseif($order->order_status_id == 2)
																<span class="badge badge-pill badge-warning py-2 text-white">{{__('frontend.delivering')}}</span>
															@elseif($order->order_status_id == 3)
																<span class="badge badge-pill badge-success py-2 text-white">{{__('frontend.completed')}}</span>
															@else
																<span class="badge badge-pill badge-danger py-2 text-white">{{__('frontend.canceled')}}</span>
															@endif
														</div> --}}

														<h6 class="title text-truncate" name="cart-name">{{__('frontend.order_list_title', ['order_id' => $order['id'], 'user_name' => $order['shop_name']])}}</h6>
														<dl class="dlist-inline small">
															<dt>{{ __('frontend.date') }}: </dt>
															<dd>
																{{ date('d/m/Y h:i:s', strtotime($order['created_at'])) }}
															</dd>
															<dt>{{ __('frontend.order_status') }}: </dt>
															<dd>
																{{ $status[$order['order_status_id']] }}
															</dd>
														</dl>
													</div>
												</figcaption>
											</figure>
										</a>
									</div>
								@endforeach
							@else
								<div class="row">
									<div class="col-sm text-secondary text-center">{{ __('frontend.no_orders_found') }}</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div> <!-- col // -->
		</div>
	</main>

	</div>
</div> <!-- row.// -->


</div><!-- container // -->
</section>






@endsection
