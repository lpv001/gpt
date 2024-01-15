@extends('frontend::layouts.main')
@section('content')
<style>
	.fa-shopping-cart{
		margin-top: 0.3em !important;
	}
	@media only screen and (max-width: 380px) {
		#delete-btn {
			width: 15em !important;
		}
	}
	@media only screen and (max-width: 360px) {
		#delete-btn {
			width: 14em !important;
		}
	}
</style>


<section class="section-content padding-y">
<div class="container">
	<div class="row">
		<main class="col-sm-8" id="cart-item">

	<div class="card">
	<table class="table table-hover shopping-cart-wrap" id="table_cart">
	<thead class="text-muted">
	<tr>
	  <th scope="col">{{__('frontend.product')}}</th>
	</tr>
	</thead>
	<tbody>

		@php
			$sub_total = 0;
			$discount = 0;
		@endphp

		@if (@$carts)
		@foreach($carts as $cart)
			@php
				$sub_total += $cart['attributes']['total'];
			@endphp

			<tr>
			<td class="px-3">
				<figure class="media">
					<div class="img-wrap"><img src="{{$cart['attributes']['image']}}" class="img-thumbnail img-sm"></div>
					<figcaption class="media-body">
						<div class="pull-left">
							<h6 class="title text-truncate" name="cart-name">{{$cart['name']}}</h6>
							<dl class="dlist-inline small">
								<dt>Unit: </dt>
								<dd>{{ $cart['attributes']['unit_name'] }}</dd>
							</dl>
							<dl class="dlist-inline small">
								<dt>{{__('frontend.shop')}}: </dt>
								<dd>{{ $cart['attributes']['shop'] }}</dd>
							</dl>
							<div class="price-wrap">
								<var class="price">USD <span class="total-price" data-total="{{$cart['attributes']['total']}}"> {{$cart['attributes']['total']}}</span> 
								<span><small class="text-muted each-price" data-price="{{$cart['attributes']['sale_price']}}">({{__('frontend.usd')}} {{$cart['attributes']['sale_price']}} {{__('frontend.each')}})</small></span></var>
							</div> 

							<div class="row">
								<div class="col-sm-6">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<button class="input-group-text add-to-cart" data-id="{{$cart['id']}}" data-unit="{{$cart['attributes']['unit_id']}}" data-type="decreasing" data-price="2" data-qty="2" data-value="-1" type="button" name="de-cat">-</button>
										</div>
										<span style="display:none" id="cart-id"></span>
										<input type="text" readonly="readonly" class="form-control text-center qty" value="{{$cart['attributes']['qty']}}" min="1" max="9">
										<div class="input-group-append">
											<button class="input-group-text add-to-cart" data-id="{{$cart['id']}}" data-unit="{{$cart['attributes']['unit_id']}}" data-type="increasing"  data-qty="2" data-value="1" data-price="2" type="button" name="in-cat">+</button>
										</div>
									</div>
		
									</div>
								</div>
							
							</div>
							
						<div class="pull-right">
						
							<form method="POST" action="{{ route('rm-cart', ['id'=> $cart['id']]) }}">
								<input name="_method" type="hidden" value="post">
								{{ csrf_field() }}
								<button  type="submit" class="btn btn-outline-danger">× {{__('frontend.remove')}}</button>
							</form>
						</div>
					</figcaption>

					
				</figure>
			</td>
		</tr>
		@endforeach
		@endif

	</tbody>
	</table>
	
	</div> <!-- card.// -->
	</main> <!-- col.// -->


		<aside class="col-sm-4" id="aside-cart">

	{{--}}
	<p class="alert alert-success">Add USD 5.00 of eligible items to your order to qualify for FREE Shipping. </p>
	--}}

	<dl class="dlist-align">
	  <dt>{{__('frontend.total_price')}}: </dt>
	  <dd class="text-right">{{__('frontend.usd')}} <span id="grantTotal">{{ $sub_total }}</span></dd>
	</dl>
	<dl class="dlist-align">
	  <dt>{{__('frontend.discount')}}:</dt>
		<dd class="text-right">{{__('frontend.usd')}} {{$discount}}</dd>
	</dl>
	<dl class="dlist-align h4 text-danger">
	  <dt>{{__('frontend.total')}}:</dt>
	  <dd class="text-right"><strong>{{__('frontend.usd')}} <span id="subtotal">{{ $sub_total }}</span></strong></dd>
	</dl>
	<hr>
	
	@if(@$carts)
		<a href="{{ route('checkout')}}" id="check-out" class="btn btn-block btn-warning">{{__('frontend.checkout')}}</a>
	@else
		<button id="check-out" class="btn btn-block btn-warning">{{__('frontend.checkout')}}</button>
	@endif
		</aside> <!-- col.// -->
	</div>
</div><!-- container // -->
</section>

<script>
	
	
	$(document).ready(function () {
		let qty_cart = $(".mycart-qty").first().text();
		let count = 0;
		count++;

		$(".add-to-cart").click(function (e) {
			let pid = $(this).data('id');
			let value = $(this).data('value');
			let unit = $(this).data('unit');
			let qty = $(this).closest('tr').find('.qty').val();
			let type = $(this).data('type');

			let total_qty = 1;
			total_qty =  type === 'increasing' ? total_qty = parseInt(qty) + 1 : total_qty = parseInt(qty) - 1;
			total_qty = total_qty <= 0 ? 1 : total_qty;
			$(this).closest('tr').find('.qty').val(total_qty);
		
			
			let product_qty = $(this).data('value');
			$.ajaxSetup({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
			});

			let totalElement = $(this).closest('tr').find('.total-price');

			let cart_qty = $(this).closest('tr').find('.qty').val();
			$.ajax({
				type: "POST",
				url: "{{ route('add-cart') }}",
				data: {
					id: parseInt(pid),
					unit: parseInt(unit),
					qty: parseInt(cart_qty),
					type: $(this).data('type'),
				},
				success: function (response) {
					// console.log(response);
					if (response.status == 200) {
						let data = response.data;
						
						totalElement.html(parseFloat(data.attributes.total));

						let total_price = 0;
						$('.total-price').each(function(key, value) {
							total_price += parseFloat($(value).text());
						});
						$('#grantTotal').html(total_price);
						$('#subtotal').html(total_price);
					}
				},
			});
		});
});
</script>
@endsection
