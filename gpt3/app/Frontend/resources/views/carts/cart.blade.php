@extends('frontend::layouts.main')
@section('content')

<style>
	@media screen and (max-width: 1199px) {
		.checkbox {
			margin: 5px !important;
		}
		.col-6 {
			margin-left: 13px;	
		}
	}

	@media screen and (max-width: 991px) {
		.col-6 {
			padding-left: 43px !important;
		}
	}

	@media screen and (max-width: 400px) {
		.cart-items {
			padding: 10px 0px !important
		}

		div.col-sm-8 > div.card {
			padding: 10px 0px !important;
		}
		.product-image {
			height: 8rem !important;
		}
		h6.mb-1 {
			font-size: 12px !important;
		}
		p.option-text {
			font-size: 13px !important;
		}
	}

	.product-image {
		width: 150px;
		height: 140px;
	}
	.option-text {
		font-size: 13px;
	}
	.increa-button {
		background-color: rgb(224, 224, 224);
		width: 18px;
		height: 18px;
		border-radius: 50%;
		text-align: center;
		align-items: center;
		align-content: center;
	}
	.text-hover {
		color: green;
	}
	.text-hover:hover {
		color: #006a4d !important;
		/* color: #FF9B37 !important; */
	}	
</style>

<section class="section-content padding-y">
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<div class="card p-3">
					<h4 class="">{{ __('frontend.shopping_carts') }} <span class="cart-qty"></span></h4>

					<div class="d-flex align-items-center mt-2">
						<input type="checkbox" name="" id="selectAll" class="select-cart">
						<p class="mb-0 px-2"> Select all</p>
					</div>
					<div class="border-bottom my-2"></div>
					
					<div class="carts">
						@foreach ($carts as $cart)
							<div class="p-3 cart-items">
								<div class="d-flex">
									<div class="col-4 p-0 d-flex">
										<div class="d-flex align-items-center mr-3 checkbox">
											<input type="checkbox" class="checkbox select-cart" name="" id="" data-id="{{$cart['id']}}">
											<input type="hidden" name="qty[]" value="{{$cart['attributes']['qty']}}">
											<input type="hidden" name="price[]" value="{{$cart['price']}}">
										</div>
										<img class="product-image rounded" src="{{$cart['attributes']['image']}}" alt="">
									</div>
									<div class="col-6 p-0">
										<h6 class="mb-1">{{$cart['name']}}</h6>
										<p class="option-text mt-2 mb-2">
											@if ($cart['attributes']['option'] > 0)
												@foreach ($cart['attributes']['option'] as $key => $item)
													<span class="font-weight-bold">{{$key}}</span> : <span class="text-capitalize">{{$item['name']}}</span>  
														@if ($key == 'Color' || $key == 'color')
															<span>
																<i class="fa fa-circle" style="color: {{$item['name']}}"  aria-hidden="true"></i>
															</span>
														@endif
													<span class="mr-2"></span>
												@endforeach
											@endif
										</p>

										<div>
											<strong class="text-danger">${{number_format($cart['price'],2)}}</strong>
										</div>

										<div class="my-3 text-lg section-qty">
											<a href="#" data-increa="false" class="btn-increa qty-decrea" data-id="{{$cart['id']}}" data-price="{{ $cart['price'] }}">
												<i class="fa fa-minus-circle  text-lg text-hover" aria-hidden="true"></i>
											</a>

											<span class="mx-1 qty" data-qty="{{$cart['attributes']['qty']}}">{{$cart['attributes']['qty']}}</span>
											

											<a href="#" data-increa="true" class="btn-increa qty-increa" data-id="{{$cart['id']}}" data-price="{{ $cart['price'] }}">
												<i class="fa fa-plus-circle text-lg text-hover" aria-hidden="true"></i>
											</a>
										</div>
									</div>
									<div class="col-2">
										<a href="#" class="btn btn-xs btn-light float-right btn-delete" data-id="{{$cart['id']}}" data-qty="{{ $cart['attributes']['qty'] }}" data-price="{{ $cart['price'] }}">
											<i class="far fa-trash-alt text-danger"></i>
										</a>
									</div>
								</div>
							</div>
						@endforeach
					</div>

					<div class="text-center p-5" style="display: none" id="empty-item">
						<h5>{{ __('frontend.msg_cart_empty') }}</h5>
						<a href="/" class="btn btn-sm btn-warning px-3">{{ __('frontend.start_shopping') }}</a>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card p-4">
					<div class="px-2">
						<h5 class="font-weight-bold">{{ __('frontend.order_summary') }}</h5>
					</div>
					{{-- <hr> --}}
					<div class="px-2 mt-3 d-flex justify-content-between">
						<p class="mb-0">{{ __('frontend.order_sub_total') }}</p>
						<p class="mb-0">$<span id="subtotal">0.00</span></p>
					</div>
					<hr>
					<div class="px-2 d-flex justify-content-between">
						<h5 class="m-0 text-danger font-weight-bold">{{ __('frontend.order_total') }}</h5>
						<h5 class="m-0 text-danger font-weight-bold">$<span id="total">0.00</span></h5>
					</div>

					<div class="checkout my-3">
						<form action="{{route('checkout')}}" method="GET" id="form_checkout">
							
							<button href="#" class="btn btn-xs btn-warning w-100 checkout" disabled="true">{{ __('frontend.btn_checkout') }}</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<script>

$(document).ready(function () {
    $("#selectAll").attr("checked", "checked");
    $(".checkbox").attr("checked", "checked");
    selectCart();
});

$(document).on("click", "#selectAll", function () {
    $(".checkbox").prop("checked", $(this).prop("checked"));
});

// delete cart item by id
$(document).on("click", ".btn-delete", function (event) {
    event.preventDefault();
    $(this).closest("div.cart-items").remove();
    if ($(".carts").children().length <= 0) {
        $("#empty-item").css("display", "block");
    }

    $.ajax({
        type: "POST",
        url: `/cart/destroy/${$(this).data("id")}`,
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function (response) {
            console.log(response.total);
            //$("#subtotal").text(response.currency[0] + " " + response.currency[1] + response.total);
            //$("#total").text(response.currency[0] + " " + response.currency[1] + response.total);
            $("#subtotal").text(response.total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $("#total").text(response.total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $(".shopping-cart-qty").text(response.data);
        },
    });
});

//
$(document).on("click", ".btn-increa", function (event) {
    event.preventDefault();
    let qtyElement = $(this).closest("div.section-qty").find("span");
    let qty = parseInt(qtyElement.text());
    let total = 0;

    qty = $(this).data("increa") ? ++qty : --qty;
    total = qty <= 0 ? 1 : qty;
    qtyElement.text(total);
    // $(this).closest('div.cart-items').find('[name="qty[]"]').val(total);

    let url = "{{ route('update-cart', ['id']) }}";
    $.ajax({
        type: "PUT",
        url: url.replace("id", $(this).data("id")),
        data: {
            _token: "{{ csrf_token() }}",
            qty: total,
        },
        success: function (response) {
            $(this)
                .closest("div.cart-items")
                .find('[name="qty[]"]')
                .val(response.sub_total);
        },
    });
});

$(document).on("click", ".select-cart", function () {
    selectCart();
});

function selectCart() {
    let total = 0;
    $(".select-cart").each(function (key, element) {
        // console.log(element)
        if ($(element).prop("checked")) {
            price = $(element)
                .closest("div.cart-items")
                .find('[name="price[]"]')
                .val();
            qty = $(element)
                .closest("div.cart-items")
                .find('[name="qty[]"]')
                .val();
            if (typeof qty !== "undefined" && typeof price !== "undefined") {
                total += price * qty;
                $("#form_checkout").append(
                    `<input type="hidden" name="carts[]" value="${$(
                        element
                    ).data("id")}">`
                );
            }
        }
    });

    $("#subtotal").text(`${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
    $("#total").text(`${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);

    if (total <= 0) {
        $(".checkout").attr("disabled", true);
    } else {
        $(".checkout").attr("disabled", false);
    }
}

$(".qty-increa").on("click", function () {
    let subTotal = parseFloat($("#subtotal").text().replace(',', ''));
    let total = parseFloat($("#total").text().replace(',', ''));

    let totalPrice = $(this).data("price") + subTotal;
    
    $("#subtotal").text(`${totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
    $("#total").text(`${totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
});

$(".qty-decrea").on("click", function () {
    let subTotal = parseFloat($("#subtotal").text().replace(',', ''));
    let total = parseFloat($("#total").text().replace(',', ''));

    let totalPrice = subTotal - $(this).data("price");
    if (totalPrice <= 0) return;

    $("#subtotal").text(`${totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
    $("#total").text(`${totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
});


</script>


@endsection
