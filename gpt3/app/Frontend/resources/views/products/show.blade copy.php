
@extends('frontend::layouts.main')
@section('content')
<style>
	.fa-shopping-cart{
		margin-top: 0.3em !important;
	}
	@media only screen and (max-width: 991px) {
		img {
			height: 21em !important;
		}
	}

	@media only screen and (max-width: 768px) {
		img {
			height: 21em !important;
		}
	}

	@media only screen and (max-width: 575px) {
		img{
			height: 21em !important;
		}
	}

	.btn-option:hover {
		background-color: rgb(255, 203, 107);
	}
</style>

<section class="section-content padding-y">
<div class="container">


<div class="row">
<div class="col-lg-12">
	<div class="card" style="border: none">
		<div class="row no-gutters">
			<aside class="col-sm-5">
	<article class="gallery-wrap">
				<div class="img-big-wrap">
					<div>
						<a href="" id="link-img" data-fancybox="">
							<img src="" class="preview-image">
						</a>
					</div>
				</div> <!-- slider-product.// -->
				<div class="d-flex justify-content-center">
					{{-- @foreach($product_image as $item) --}}
						<div class="img-small-wrap">
							<div class="item-gallery"> <img src="{{$product['image_name']}}" data-id="{{$product['id'] }}" class="w-100 nav-image"></div>
						</div> <!-- slider-nav.// -->
					{{-- @endforeach	 --}}
				</div>
	</article> <!-- gallery-wrap .end// -->

	</aside>

	<aside class="col-sm-7">
		<article class="p-4">
			<h3 class="title mb-3" id="{{$product['id']}}">{{ $product['name'] }}</h3>
					<dl>
						{{-- <dt>{{__('frontend.description')}}</dt> --}}
						<dd>
							{{ $product['description'] }}
						</dd>
					</dl>
					<hr>

					<div class="row">
						<div class="col-sm-2">
							{{__('frontend.category')}}
						</div>
						<div class="col-sm-10">
							<span>: {{$product['category_name']}}</span>
						</div>
					</div>

					@if (count($product['options']) > 0)
						@foreach ($product['options'] as $option)
							<div class="row my-2">
								<div class="col-sm-2">
									{{$option['name']}}
								</div>
								<div class="col-sm-10">
									:
									@if (count($option['values']) > 0)
										@foreach ($option['values'] as $item)
											<a class="btn btn-sm btn-option mx-2 border border-primary px-3 py-1">{{$item['name']}}</a>
										@endforeach
									@endif
								</div>
							</div>
						@endforeach
					@endif
					
					{{-- <div class="row">
						<div class="col-sm-2">
							Option
						</div>
						<div class="col-sm-10">
						</div>
					</div> --}}
			<hr>
			<div class="row mt-4">
				<div class="col-sm-5">
					{{-- <button id="btnAdd" class="btn btn-warning btn-bg add-to-cart" data-qty="1" data-id="{{$product['id']}}">Add to cart</button> --}}
					<div class="dl-cart">
						<dl id="{{ $product['id'] }}" class="dlist-inline small" style="font-size: 20px;">
							<a id="{{ $product['id'] }}" data-qty="1" data-id="{{$product['id']}}" class="p-3 add-to-cart"  style="cursor: pointer; font-size:2rem">
								<i class="fas fa-plus-circle text-green"></i>
							</a>
							<span class="quantity" id="cartQuantity" style="font-size:2rem">1</span>
							<a cidart="{{ $product['id'] }}" data-qty="-1" data-id="{{$product['id']}}" class="p-3 add-to-cart" style="cursor: pointer; font-size:2rem">
								<i class="fas fa-minus-circle text-green"></i>
							</a>
						</dl>
					</div>
				</div> <!-- col.// -->
			</div> <!-- row.// -->
			
			{{-- <hr> --}}

		</article> <!-- card-body.// -->
	</aside> <!-- col.// -->
</div> <!-- row.// -->
</div> <!-- card.// -->

<div class="container mt-3 bg-white" style="border: none">
	{{-- <div class="card-body p-2 rounded text-center"> --}}
		<div class="row p-4">
			<div class="col-sm-4">
				<h4>{{$product['shop_name']}} Store</h4>

				<div class="mt-2 col-sm-8 p-0">
					<img class="img-thumbnail" src="{{ $shop->logo_url ?? '' }}" alt="shop_logo" >
				</div>

				<div class="mt-2">
					<span class="small-text text-secondary">Tel:(+855) {{'e' ?? ''}}</span>
				</div>
				<div class="mt-1">
					<span class="small-text text-secondary">Address: {{ 'asdasd' ?? ''}}</span>
				</div>
				<div class="mt-1">
					<a href="{{ route('vist.shop', $product['shop_id']) }}" class="btn btn-sm btn-warning mx-auto px-5">Visit Store</a>
				</div>
			</div>
			<div class="col-sm-8">
				<h4>Location</h4>
				<div id="map" style="height: 300px;"></div>
			</div>
		</div>
	{{-- </div> --}}
</div>
</div>
</div> <!-- row.// -->

	<div class="container bg-white mt-3 p-4">
		<h4>Seller Recommendations</h4>

		<div class="row">
			@if (@$related_products)
				@include('frontend::products.components.item', ['products' => $related_products, 'classColumn' => 'col-md-3 items mb-3'])
			@endif
		</div>
	</div>
	{{-- <div class="container bg-white p-4">
		<h4>More Products</h4>

		<div class="row">
			<div class="col-sm-3">
				product 1
			</div>
			<div class="col-sm-3">
				product 2
			</div>
			<div class="col-sm-3">
				product 3
			</div>
			<div class="col-sm-3">
				product 4
			</div>
		</div>
	</div> --}}
	
</div><!-- container // -->
</section>
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjD6jIchE8joKfi6Risjv995PoJQPxVxw&callback=initMap&libraries=&v=weekly"
      async
    ></script>
<script>
	$(document).ready(function(){
		let img = $('.nav-image').first();
		$('.preview-image').attr('src', img.attr('src'));
		$('#link-img').attr('href', img.attr('src'));
	});

	$(document).on('click', '.nav-image', function() {
		$('.preview-image').attr('src', $(this).attr('src'));
		$('#link-img').attr('href', $(this).attr('src'));
	});

	$(document).ready(function(){
		$('input[name=unit]:first').attr('checked', true);
		$('#salePrice').text( (parseFloat($('input[name=unit]:first').attr('data-price'))).toFixed(2) );
		$('#unitName').text( $('input[name=unit]:first').attr('data-name')	 );
		
		// $('.starrr').starrr({
		// 	rating: 4
		// });

	});

	$(document).on('click', '.radio-unit', function(){
		$('#salePrice').html($(this).attr('data-price'));
	});

	let qty_cart = $(".mycart-qty").first().text();
	let count = 0;


$(document).on('click', '.add-to-cart', function() {
	let cart_qty = $(this).closest('div').find('.quantity').text();


	let pid = $(this).attr("data-id");
	var unit = $("input[type='radio']:checked").val();

	if (typeof(unit) == 'undefined') {
		$('#unit-msg').removeClass('invisible').addClass('visible');
		return;
	}

	$('#unit-msg').removeClass('visible').addClass('invisible');
	$('#btnAdd').hide();


	var ele = $(this).closest("div").find(".dl-cart").addClass("d-inline").show();
	$("#".pid).removeClass("invisible").addClass("visible");

	var mycart_qty = $('#cartQuantity').text();
	var qty = $(this).attr('data-qty');
	
	if (parseInt(qty) <= 0)
	{
		count--;

	} else {
		count++;
	}

	var calulate_qty = count > 0 ? count : 1;
	$('#cartQuantity').text( calulate_qty );
	// let total_qty = parseInt(qty_cart) + parseInt(calulate_qty);
	let qty_cart = $('#cart-qty').text();
	$('#cart-qty').text(parseInt(qty_cart) + 1);

	let total_qty = parseInt(calulate_qty);
	$(".mycart-qty").text( total_qty );

	$.ajaxSetup({
		headers: {
			"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
		},
	});
	$.ajax({
		type: "POST",
		url: "{{ route('add-cart') }}",
		data: {
			id: pid,
			unit: unit,
			qty: total_qty,
		},
		success: function (response) {
			console.log(response.data)
			// $(".mycart-qty").text( parseInt(qty_cart) + count);
		},
	});
});

	function initMap() {
		console.log('asdsad')
		// The location of Uluru
		const uluru = { lat: {!! $shop['lat'] !!}, lng: {!! $shop['lng'] !!} };
		// The map, centered at Uluru
		const map = new google.maps.Map(document.getElementById("map"), {
			zoom: 15,
			center: uluru,
		});
		// The marker, positioned at Uluru
		const marker = new google.maps.Marker({
			position: uluru,
			map: map,
		});
	}
</script>
@endsection
