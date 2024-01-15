
@extends('frontend::layouts.main')
@section('content')
<style>
	@media screen and (max-width: 990px) {
		img#img-themail {
			height: 22rem !important;
		}
		h2 {
			font-size: 20px;
		}
		h4 {
			font-size: 18px;
			line-height: 28px !important;
		}
		h6 {
			/* font-size: 14px; */
			margin-bottom: 2px !important;
		}
		p {
			font-size: 13px;
		}
		.option {
			padding: 0px 13px !important;
			font-size: 13px !important;
		}
		.star {
			font-size: 12px;
		}
		.quantity {
			margin: 10px 0px !important; 
		}
		.fa-minus-circle, .fa-plus-circle {
			font-size: 20px !important;
		}
		.submit-card {
			padding: 1px 10px !important;
		}
		.container {
			margin-top: 10px !important
		}
		.section-content {
			padding: 10px 0px !important;
		}
	}

	@media screen and (max-width: 990px) { 
		.container {
			/* background-color: red !important; */
			margin-left: 46px !important;
			max-width: 90%;
			
			/* margin-right: 33px !important; */
		}
		.product-card {
			width: 147px !important;
		}
		.more-product-lists {
			width: 46rem !important;
		}
		.product-imgs {
			height: 150px !important
		}
	}

	@media screen and (max-width: 792px) { 
		.container {
			/* background-color: red !important; */
			margin-left: 5px !important;
			max-width: 90%;
		}
		img#img-themail {
			height: 17rem !important;
		}
		.product-card {
			width: 147px !important;
		}
		.more-product-lists {
			width: 46rem !important;
		}
		.product-imgs {
			height: 150px !important
		}
	}

	@media screen and (max-width: 610px) { 
	 	.col-5 {
			 flex: none !important;
			max-width:100% !important;
		}
		.col-7 {
			flex: none !important;
			max-width: none !important;
		}
		.container {
			margin-left: 23px !important;
		}
		.img-option-thumail {
			margin:0px auto !important;
		}
		/*
		.item-shop {
			display: none !important;
		}
		*/
		.more-product {
			margin-left: 15px !important;
		}
		div.more-product > div.p-4 {
			margin: 0 !important;
			padding: 0 !important;
		}
		img#img-themail {
			height: 20rem !important;
		}
		.submit-card {
			width: 100%;
			padding: 6px 6px !important;	
		}
		.submit {
			width: 100%;
			padding: 0 !important;
			margin: 0 !important;
		}
	}

	@media screen and (max-width: 414px) {
		div.container > div.row > div.col-6 {
			flex: none !important;
			max-width: none !important;
		}
	}

	@media screen and (max-width: 375px) {
		.col-6 {
			flex: none !important;
			max-width: 100% !important;
		}
		.product-card {
			width: 139px !important;
    		margin: 7px 7px !important;
		}
		.h3 {
			font-size: 1.2rem;
		}
	}

	#img-themail {
		width: 100% !important; 
		height:30rem !important
	}

	.fa-minus-circle, .fa-plus-circle {
		font-size: 25px;
	}
	
	.zoom {
			display:inline-block;
			position: relative;
		}
		
		/* magnifying glass icon */
		.zoom:after {
			content:'';
			display:block; 
			width:33px; 
			height:33px; 
			position:absolute; 
			top:0;
			right:0;
			background:url(icon.png);
		}
		.zoom:hover {
			cursor: pointer;
		}

		.zoom img {
			display: block;
		}
		.zoom img::selection { background-color: transparent; }
		h4 {
			line-height: 38px;
		}
		div.option:hover{
			background-color: #fff;
		}
		.option:hover {
			background-color: #fd7e14;
			color: #fff;
		}
		.option-active {
			background-color: #fd7e14;
			color: #fff;
		}
		.option-selected {
			border: 2px solid orange !important;
		}
		.option-image:hover {
			cursor: pointer;
			border: 2px solid orange !important;
		}
		.active-image {
			border: 2px solid orange !important;
		}
		.product-card {
			width: 13rem;
		}
		.product-imgs {
			width:100%;
			height: 170px
		}
		p.description {
			white-space:inherit !important;
		}
		.scrolls {
			max-width: 100%;
			overflow-x: scroll;
			overflow-y: hidden;
			white-space:nowrap
		}

		.product-card {
			height: 252px;
			width: 196px !important;
		}
</style>
@php
	$images = [];
	if (count($product['images']) > 0) {
		foreach ($product['images'] as $key => $image) {
			$images[] = $image['image_name']	;
		}
	}
@endphp
<section class="section-content padding-y bg-white my-3">
	<div class="container">
		<div class="row">
			<div class="col-6">
				<div class="w-100">
					<span class='zoom' id='ex1'>
						<img 
							src='{{ $product['image_name']}}' 
							id="img-themail" 
							alt='{{$product['name']}}'
							class="img-preview"/>
					</span>
				</div>
				<div class="w-100 d-flex scrolls" >
					{{-- <div class="owl-carousel owl-theme"> --}}
						
						@if (count($images) > 0)
							@foreach ($images as $item)
							{{-- <div class="item option-image p-1" data-img="{{ $item }}"> --}}
								<img class="img-option-thumail option-image mr-2" data-img="{{ $item }}" src="{{$item}}" style="width:70px; height:70px;" alt="">
							{{-- </div> --}}
							@endforeach							
						@endif
					{{-- </div> --}}
				</div>
			</div>

			<div class="col-6">
				<div class="d-flex align-items-start flex-column" style="min-height: 100%">
					<div class="product-detail">
						<h4 class="h4-title font-weight-bold">{{$product['name']}}</h4>

						<div class="pr-4">
							<p class="description">{{$product['description']}}</p>
						</div>

						<div>
							<h2 class="text-danger font-weight-normal ">$ <span id="price">{{ $product['unit_price'] }}</span></h2>
						</div>

						<div class="review">
							<h6 class=""><span class="font-weight-bold">{{ __('frontend.reviews') }}</span>  : 0 Orders</h6>
							<span class="star">
								<i class="fa fa-star text-warning" aria-hidden="true"></i>
								<i class="fa fa-star text-warning" aria-hidden="true"></i>
								<i class="fa fa-star text-warning" aria-hidden="true"></i>
								<i class="fa fa-star text-warning" aria-hidden="true"></i>
								<i class="fa fa-star text-warning" aria-hidden="true"></i>
							</span>
							<span class="mx-3">0.0 rate</span>
						</div>
						
						@if (count($product['options']) > 0)
							@foreach ($product['options'] as $item)
								@php
									$variant_price = 0;
								@endphp
								<div class="options option-fields mt-3 ">
									<div class="option-fields d-flex align-items-center">
										<h6 class="mr-3 mb-0 text-capitalize">{{$item['name']}} : </h6>
										<span class="option-name"></span>
									</div>

									<div class="d-flex flex-wrap w-60 options">
										@if ($item['values'] > 0)
										@foreach ($item['values'] as $value)
											@if ($value['image_name'])
												<div class="mr-2 p-1 border rounded option-image option" 
													data-name="{{$value['name']}}" 
													data-img="{{$value['image_name']}}" 
													data-option-type="{{$item['name']}}"
													data-id="{{$value['id']}}">
													<div  style="width: 60px; height:60px;
													background-image: url('{{$value['image_name']}}');
													background-repeat:no-repeat;
													background-size:contain;
													max-width:100%;"
													>
													</div>
												</div>
											@else
												<a href="#" class="px-3 mr-2 mb-2 border border-warning rounded option" 
												data-name="{{$value['name']}}" 
												data-option-type="{{$item['name']}}"
												data-id="{{ $value['id'] }}">{{$value['name']}}</a>
											@endif
										@endforeach
										<input type="hidden" name="option_vaue_id[]" value="">
									@endif
									</div>
								</div>
							@endforeach
						@endif

						<div class="quantity my-4">
							<h6>{{ __('frontend.quantity') }}</h6>

							<div class="d-flex align-items-center my-3">
								<a href="#" class="mr-2 d-flex align-items-center btn-qty" data-value="true">
									<i class="fa fa-plus-circle text-warning" aria-hidden="true"></i>
								</a>
									<span class="mr-2" id="cart-qty-number" style="font-size:20px">1</span>
									<input type="hidden" name="qty" value="1" >
								<a href="#" class="mr-2 d-flex align-items-center btn-qty" data-value="false">
									<i class="fa fa-minus-circle text-warning" aria-hidden="true"></i>
								</a>
							</div>
						</div>
					</div>
					
					<div class="mt-2 p-2 submit">
						{{-- <button class="btn btn-warning btn-xs px-4 mr-3 submit-card" data-type="buynow" id="shopNow">{{ __('frontend.buy_now') }}</button> --}}
						<button class="btn btn-warning btn-xs px-4 submit-card mr-3" data-type="addcard" id="add_to_card">{{ __('frontend.add_to_cart') }}</button>

						<span id="message" class="text-danger invisible">
							<i class="fa fa-info-circle" aria-hidden="true"></i> 
							Please provide information !
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

{{--
@include('frontend::products.shop_product')
--}}

<div class="container more-product card bg-white my-4 rounded p-3">
	<div class="p-4">
		<div><h3>{{ __('frontend.more_products') }}</h3></div>
			<div class="d-flex d-flex flex-wrap more-product-lists" style="width: 100% !important;">
				@foreach ($related_products as $item)
				<a href="{{ route('product.show', $item['id']) }}">
					<div class="card mr-3 mt-3 shadow-sm product-card">
						<div class="card-body p-0">
							<img 
								src="{{$item['image_name'] ?? asset('img/logo.png')}}" 
								class="product-imgs"
								alt="">
	
							<div class="p-2 pt-0 text-left">
								<p class="title py-2 m-0">
									{{ $item['name'] }}
								</p>
								<div class="d-flex justify-content-between align-items-center">
									<span class="m-0 text-danger font-weight-bold text-center" style="">USD ${{ $item['unit_price'] }} / {{ $item['unit_name'] }}</span>
								</div>
							</div>
						</div>
					</div>
				</a>
				@endforeach
			</div>
	</div>
</div>


	<div id="snackbar">{{ __('frontend.msg_select_product_options') }}</div>

	<script>
		var imageUlr = '{{ $product["image_name"] }}';
		const product = {!! json_encode($product) !!}
		const productVariants = product.variants;
		let variants = [];
		var options = {};		

		$(document).ready(function(){
			$('#ex1').zoom();
		});

		$(document).on('click', '.option-image', function () {
			
			$('#img-themail').attr('src', $(this).data('img')); // change image thumail to option image
			$('#ex1').zoom();
		})

		$(document).on('click','.option', function(event) {
			event.preventDefault();
			$(this).closest('div').find('a').each(function(key, element) {
				$(element).removeClass('option-active');
			});

			let optionType = $(this).data('option-type');
			let optionId = $(this).data('id');
			// set value to hidden input option
			options[optionType] = optionId;
			// console.log(options);
			
			let child = $(this).find('div').length;
			if (child == 0) { // if option has no image
				$(this).addClass('option-active');
			} else {
				$('.option-image').removeClass('option-selected');
				$(this).addClass('option-selected');
			}

			// check if product have any variants
			if (productVariants.length > 0) {
				// set default price
				let price = product.unit_price;
			
				let variantOptionSelected = [];
				productVariants.forEach(
					variant => {
						if (variant.option_value_id === options[optionType] ) {
							// filter variant by id
							variantOptionSelected = productVariants.filter(item => item.variant_id == variant.variant_id);
							//remove key take only value of option
							let opt = Object.values(options);
							// get variant has option that selected within same variant options
							let last_filter = variantOptionSelected.filter((last, index) => {
								return last.option_value_id == opt[index];
							} );
							// check if option selected and variant option is equal
							if (last_filter.length == variantOptionSelected.length) {
								price = variant.variant_price.toFixed(2);
								variants = last_filter; // update variants
							} else {
								variants = [];
							}
						} 
				});
				// $('')
				$('#price').text(price);
			}

			let image = $(this).data('img'); 
			if (image != undefined || image != '') { // 
				$('#img-themail').attr('src', image); // change image thumail to option image
				$('#ex1').zoom();
			}
			// show option name when is selected
			$(this).closest('.option-fields').find('.option-name').text($(this).data('name'));
		});

		$(document).ready(function() {
			$('.owl-carousel').owlCarousel({
				loop:true,
				margin:10,
				nav:true,
				dots: false,
				responsive:{
					0:{
						items:1
					},
					// 400: {
					// 	items:4
					// },
					500: {
						items:4
					},
					600:{
						items:3
					},
					1000:{
						items:5
					}
				}
			});
		});

		$(document).on('click', '.btn-qty', function(event) {
			event.preventDefault();
			let qty = parseInt($('#cart-qty-number').text());
			let total = 0;
			qty = $(this).data('value') ? ++qty : --qty;
			total = qty <= 0 ? 1 : qty;
			$('#cart-qty-number').text(total); //
			// update cart number
			let catNum = parseInt($('.shopping-cart-qty').text());
			$(this).closest('div').find('[name="qty"]').val(total);
		});

		$(document).on('click', '.submit-card', function() {
			let is_valid = false;
			let qty = $('[name="qty"]').val();
			//remove key take only value of option
			let option_value = Object.values(options);

			is_valid = product.options.length == option_value.length ? true : false;

			if (product.options.length <= 0) {
				is_valid = true;
			}
			
			if (is_valid) {
				$.ajax({
					type: "POST",
					url: '{{ route('add-cart') }}',
					data: {
						"_token": "{{ csrf_token() }}",
						"option_value_id" : option_value,
						"variants" : variants,
						'qty':qty,
						"product": product,
						"image" : imageUlr
					},
					success: function(response) {
						$('.shopping-cart-qty').text(response.data);
					}
				});
			} else {
				// show snack bar
				var x = document.getElementById("snackbar");
				x.className = "show";
				setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
			}
			
		});
	</script>
@endsection
