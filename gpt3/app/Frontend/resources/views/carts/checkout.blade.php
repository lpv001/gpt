@extends('frontend::layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">

<section class="section-content padding-y">
	<div class="container ">

		{{-- {{ dd($errors) }}i --}}
		@if ($errors)
			@if ($errors->any())
				<div class="error alert alert-danger mb-0">
					 {{ __('frontend.msg_form_errors') }}
				</div>
			@endif
		@endif

		{{-- Form --}}
			<form action="{{ route('excute-order') }}" method="post" class="" id="placeOrder">
				@csrf
			<div class="row">
				<div class="col-sm-8">
					<div class="card p-4 my-4">
						<div class="d-flex justify-content-between mb-3">
							<h5 class="font-weight-bold mb-0">{{ __('frontend.select_delivery_address') }}<span class="text-danger">*</span></h5>
							@if ($errors->has('delivery_address_id'))
								<span class="text-danger font-weight-normal ml-3">{{ __('frontend.please_select_delivery_address') }}</span>
							@endif
							<a href="{{ route('my.location') }}" class="btn btn-warning border-radius-50 font-small " >
								<i class="fa fa-plus" aria-hidden="true"></i>{{ __('frontend.new_address') }}
							</a>
							<input type="hidden" name="delivery_address_id" value="{{old('delivery_address_id')}}" required>
						</div>
						
						<div class="row">
							@foreach ($my_address as $address)
								<div class="col-sm-6 pr-0 mb-2 delivery-addresses" id="address{{$address['id']}}" data-id="{{ $address['id'] }}">
									<div class="border my-2 mr-1 p-3 rounded addresses">
										<div class="d-flex justify-content-between">
											<span class="font-weight-bold">{{ __('frontend.' . strtolower($address['tag'] . '_location')) }}</span>
											<div>
												{{-- <span class="edit-addresses"><i class="fas fa-pencil-alt mr-1"></i> Edit</span> --}}
												<a class="delete-addresses text-danger ml-2" data-id="address{{ $address['id'] }}" data-value="{{ $address['id'] }}">
													<i class="fas fa-trash-alt mr-1"></i>{{ __('frontend.delete') }}
												</a>
											</div>
										</div>
										<div class="mt-2 text-address">
											{{$address['address']}}
										</div>
									</div>
								</div>
							@endforeach
							
							<div class="col-sm-6 pr-0 mb-2">
								<a href="#">
									<div class="border my-2 mr-1 p-3 py-4 rounded text-center addresses more-addresses d-flex justify-content-center align-self-center">
										<div class="d-flex justify-content-center align-self-center">
											<i class="fas fa-map-marker-alt h5 mr-3"></i>
											<h5 class="">{{ __('frontend.more_locations') }}</h5>
										</div>
									</div>
								</a>
							</div>
							
						</div>
					</div>

					{{-- Delivery Options --}}
					<div class="card p-4 my-4">
						<div class="d-flex">
							<h5 class="font-weight-bold">{{ __('frontend.delivery_method') }}<span class="text-danger">*</span></h5>
							@if ($errors->has('delivery_option_id'))
								<span class="text-danger font-weight-normal ml-3">{{ __('frontend.select_delivery_option') }}</span>
							@endif
						</div>
						@include('frontend::carts.checkouts.checkout_method', [
							'title' => '', 
							'data' => $delivery_options,
							'input_name' => 'delivery_option_id',
							'class_name' => 'delivery-option'
						])
					</div>
					
					{{-- Payment Options --}}
					<div class="card p-4 my-4">
						<div class="d-flex">
							<h5 class="font-weight-bold">{{ __('frontend.payment_method') }}<span class="text-danger">*</span></h5>
							@if ($errors->has('payment_option_id'))
								<span class="text-danger font-weight-normal ml-3">{{ __('frontend.select_payment_option') }}</span>
							@endif
						</div>

						@include('frontend::carts.checkouts.checkout_method', [
							'title' => 'Payment Method', 
							'data' => $payment_options,
							'input_name' => 'payment_option_id',
							'class_name' => 'payment-option'
						])
						<input type="hidden" name="payment_flag">
					</div>

					{{-- Cart list --}}
					@include('frontend::carts.checkouts.cart_item_list_disable', 
					['carts' => $carts])
				</div>
				{{-- End Left side --}}

				{{-- Right Side --}}
				<div class="col-sm-4">
					<div class="card p-4 my-4">
						<h5 class="font-weight-bold">{{ __('frontend.order_review') }}</h5>
						<div class="my-2 d-flex justify-content-between">
							<p><i class="fa fa-tag fa-lg text-warning text-sm mr-2"></i>{{ __('frontend.select_coupon') }}</p> 
							<a href="#">
								<i class="fa fa-caret-down text-warning" aria-hidden="true"></i>
							</a>
						</div>

						<div class="error alert alert-danger" id="couponError">
							<span>{{ __('frontend.your_code_is_invalid') }}</span>
						</div>

						<div class="success alert alert-success" id="couponSuccess">
							<span>{{ __('frontend.your_code_is_valid') }}</span>
						</div>

						<div class="my-2 d-flex justify-content-between">
							<input type="text" name="coupon" class="form-control rounded-0" id="">
							<a href="#" class="btn btn-warning rounded-0 rounded-right apply-coupon" style="width: 40%"> {{ __('frontend.apply') }} </a>
						</div>

						<hr>

						<!-- Total items -->
						<div class="d-flex justify-content-end mb-2">
							<span>{{ __('frontend.item_total') }}: $</span>
							<span id="itemTotal">{{ number_format($total,2)  ?? 0.00 }}</span>
						</div>

						<!-- Deliveries -->
						<div class="d-flex justify-content-end mb-2">
							<span>{{ __('frontend.delivery_fee') }}: $</span>
							<span id="deliveryTotal">0.00</span>
						</div>

						<!-- Total items -->
						<div class="d-flex justify-content-end mb-2">
							<span>{{ __('frontend.total') }}: $</span>
							<span id="subTotal">{{ number_format($total,2)  ?? 0.00 }}</span>
						</div>
						
						<!-- Discounts -->
						@php
							$discountTotal = 0;
						@endphp

						@if (count(@$discounts) > 0)
							@foreach ($discounts as $discount)
								@php
									$discountTotal += number_format(($total * $discount['value']) / 100, 2);
								@endphp

								<div class="d-flex justify-content-end mb-2">
									<span class="">{{ $discount['name'] }} : </span>
									<span class="ml-3 text-danger font-weight-bold discounts" data-value="{{ number_format(($total * $discount['value']) / 100, 2) }}">
									$ -{{number_format(($total * $discount['value']) / 100, 2) }}
									</span>
								</div>
							@endforeach
						@endif

						<div id="couponValue">
							<div class="d-flex justify-content-end mb-2 coupon-discounts">
								<span class="">{{ __('frontend.coupon') }}<span id="couponPriceLabel"></span> : </span>
								<span class="ml-3 text-danger font-weight-bold discounts">$ -<span id="CouponPrice" data-value=""></span></span>
							</div>
						</div>
						<input type="hidden" name="discountTotal" id="discount" value="{{ $discountTotal }}">
						<hr>

						<!-- Total -->
						@php
							$total = $total - $discountTotal;
						@endphp
						<div class="my-2 d-flex justify-content-end">
							<h4 class="font-weight-bold">{{ __('frontend.order_total') }}
								$<span id="orderTotal">{{ number_format($total,2)  ?? 0.00 }}</span>
							</h4>
							<input type="hidden" name="order_total" id="order_total" value="{{ $total }}">
						</div>

						<!-- button -->
						<div class="my-2">
							<input type="hidden" name="date" value="">
							<input type="hidden" name="time" value="">
							<button class="btn btn-xs btn-warning btn-submit w-100">{{ __('frontend.place_order') }}</button>
						</div>
					</div>
				</div>
				{{-- End Right Side --}}

			</div>
			</form>
	</div>
</section>
@if (@$errors->any())
	<script>
		console.log("{{ $errors->first() }}");
	</script>
@endif

<script>
	let checkboxs = [];

	$("#couponError").hide();
	$("#couponSuccess").hide();
	$("#couponValue").hide();
	
	// check selected delivery address
	let delivery_address_id = $("[name=delivery_address_id]").val();
	if ($.isNumeric(delivery_address_id)) {
    $("#address"+delivery_address_id).children("div").addClass("addresses-active");
  }
	
	// Get default radio options value
	let delivery_option_cost = $("input[name='delivery_option_id']:checked").attr('data-cost');
	if ($.isNumeric(delivery_option_cost)) {
    calculateAndShowTotal();
  }

	function calculateAndShowTotal() {
		// Calculate Totals
		let itemTotal = parseFloat($("#itemTotal").text().replace(',', ''));
		let delivery_cost = parseFloat($(".delivery-option:checked").attr('data-cost'));
		let discountTotal = parseFloat($("[name=discountTotal]").val());
		let subTotal = itemTotal + delivery_cost;
		let orderTotal = subTotal - discountTotal;
		
		// Shows
		$("#deliveryTotal").text(delivery_cost.toFixed(2));
		$("#subTotal").text(subTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
		$("#orderTotal").text(orderTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
		$("[name=order_total]").val(orderTotal);
	}

	// Delivery option
	$(".delivery-option").on("click", function () {
		calculateAndShowTotal();
	});

	// Apply coupon
	$(document).on("click", ".apply-coupon", function (event) {
		event.preventDefault();
		
		let CouponPrice = parseFloat($("#CouponPrice").text());
		if (CouponPrice > 0) {
			return;
		}
		
		let coupon = $("[name=coupon]").val();
		let discountTotal = parseFloat($("[name=discountTotal]").val());
		
		if (coupon == undefined || coupon == "") {
			$("#couponError").show();
			$("#couponError").children("span").html("Invalid Coupon Code !");
			return;
		}
		
		// Verify coupon
		let CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
		$.ajax({
			/* the route pointing to the post function */
			url: "promotion/apply-promotion-code",
			type: "POST",
			/* send the csrf-token and the input to the controller */
			data: {
				_token: CSRF_TOKEN,
				code: coupon,
			},
			dataType: "JSON",
			/* remind that 'data' is the response of the AjaxController */
			success: function (response) {
				if (response.status) {
					$("#couponSuccess").show();
					$("#couponError").hide();
					//$("#couponSuccess").children("span").html(response.message);
					let itemTotal = parseFloat($("#itemTotal").text());
					
					// console.log(response);
					let data = response.data;
					
					// caculate discount
					let discount = (itemTotal * data.value) / 100;
					if (data.flag == 2) {
						discount = data.value; // the value is not percentag.
					}
					
					// Update total discount value
					discountTotal += discount;
					$("[name=discountTotal]").val(discountTotal);
					
					// Calculate Totals
					let subTotal = parseFloat($("#subTotal").text());
					let total = subTotal - discountTotal;
					
					// Show price
					$("#couponValue").show();
					$("#couponPriceLabel").text(" " + data.value + data.symbol);
					$("#CouponPrice").text(discount.toFixed(2));
					$("#orderTotal").text(total.toFixed(2));
					$("[name=order_total]").val(total);
				} else {
        			$("#couponError").show();
        			$("#couponSuccess").hide();
        			//$("#couponError").children("span").html("Invalid Coupon Code!");
				}
			},
		});
	});

	$("#placeOrder").submit(function (event) {
		var today = new Date();
		var date = `${today.getFullYear()}/${
			today.getMonth() + 1
		}/${today.getDate()}`;
		var time =
			today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
		$('[name="date"]').val(date);
		$('[name="time"]').val(time);
	});

	function calulateTotal(total) {
		const discount = parseFloat($("#discount").val());
		let totalPrice = total - discount;
		$("#totalPrice").text(totalPrice.toFixed(2));
	}

  // Delete delivery address
	$(".delete-addresses").on("click", function () {
		let id = $(this).data("id");
		$(`#${id}`).remove();

		let url = "{{ route('my-delivery-addresses.destroy', ['id']) }}";
		console.log(url)
		$.ajax({
			type: "DELETE",
			url: url.replace('id', $(this).data('value')),
			data: {
				_token: "{{ csrf_token() }}",
			},
			success: function (reponse) {
				console.log(reponse);
			},
		});
	});

  // Select delivery address
	$(".delivery-addresses").on("click", function () {
		$(".delivery-addresses").hide();
		$(".delivery-addresses").children("div").removeClass("addresses-active");

		$(this).children("div").addClass("addresses-active");
		$(this).show();
		$("[name=delivery_address_id]").val($(this).data("id"));
	});

	$(".more-addresses").on("click", function () {
		$(".delivery-addresses").show();
	});

	$("input[name=payment_option_id]").on("change", function () {
		$("input[name=payment_flag]").val($(this).data("flag"));
	});
</script>

@endsection