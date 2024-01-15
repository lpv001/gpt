
@extends('frontend::layouts.main')
@section('content')
<style>
	.fa-shopping-cart{
		margin-top: 0.3em !important;
	}

	.owl-dots {
		display: none;
	}

	@media only screen and (max-width: 991px) {
		.banner-silder {
			height: 11em !important;
		}
	}

	@media only screen and (max-width: 768px) {
		.banner-silder {
			height: 11em !important;
		}
	}

	@media only screen and (max-width: 575px) {
		/* .banner-silder {
			height: 11em !important;
		} */
	}
</style>
@php
    if (session()->has('locale')) {
      \App::setLocale(session()->get('locale'));
    } else {
      \App::setLocale('en');
    }
@endphp
<!-- ========================= SECTION MAIN ========================= -->
<section class="section-main bg1 padding-bottom">
<div class="container banner-section">
<div class="search-box">
				{{-- <form action="{{ route('search')}}" method="get">
					<div class="input-group w-100">
						<input type="text" id="q" name="q" class="form-control" style="width:40%;" placeholder="Search">

						<div class="input-group-append">
						<button class="btn btn-warning" type="submit">
							<i class="fa fa-search"></i>
						</button>
						</div>
					</div>
				</form>  --}}
				<!-- search-wrap .end// -->
			</div> <!-- col.// -->
<div class="row no-gutters bAdd to cart bAdd to cart-top-0 bg-white">
</div>
<main class="col-lg-24-24 px-0">
<!-- ========= intro aside ========= -->
<div class="row no-gutters">
	<div class="col-lg-12 col-md-12">
<!-- ================= main slide ================= -->


<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">

	@if (@$banners)
		@foreach($banners as $key => $value)
		{{-- {{dd($value)}} --}}
		<div class="carousel-item {{$key == 1 ? 'active' : ''}}">
			<img class="d-block w-100" src="{{ $value['image'] }}" style="height: 400px !important">
		</div>
		@endforeach
	@endif
  </div>

  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<!-- ============== main slidesow .end // ============= -->
	</div> <!-- col.// -->
</div> <!-- row.// -->
<!-- ======== intro aside ========= .// -->
</main> <!-- col.// -->
</div> <!-- row.// -->
</div> <!-- container .//  -->
</section>
<!-- ========================= SECTION MAIN END// ========================= -->


<!-- ========================= SECTION ITEMS ========================= -->
@if (@$new_products)
<section class="section-request ">
	<div class="container">
		<!-- New Product -->
		<header class="section-heading heading-line1">
			<div class="row">
				<div class="col-sm-6">
					<h4 class="title-section bg1 text-uppercase font-weight-bold pull-left m-0">{{__('frontend.new_product')}}</h4>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{url('/search')}}" class="pull-rigth"> {{__('frontend.see_all')}}</a>
				</div>
			</div>
		</header>
		<div class="row-sm">
			<div class="owl-carousel owl-theme">
				{{-- @foreach ($new_products as $key => $value) --}}
					@include('frontend::products.components.item', ['products' => $new_products])
				{{-- @endforeach --}}
			</div>
		</div> <!-- row.// -->
	</div><!-- container // -->
</section>
@endif


@if (@$random_products)
<section class="section-request mt-4">
	<div class="container">
		<!-- New Product -->
		<header class="section-heading heading-line1">
			<div class="row">
				<div class="col-sm-6">
					<h4 class="title-section bg1 text-uppercase font-weight-bold pull-left m-0">{{__('frontend.popular_product')}}</h4>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{url('/search')}}" class="pull-rigth"> {{__('frontend.see_all')}}</a>
				</div>
			</div>
		</header>
		<div class="row-sm">
			<div class="owl-carousel owl-theme">
				{{-- @foreach ($random_products as $key => $value) --}}
					@include('frontend::products.components.item', ['products' => $random_products])
				{{-- @endforeach --}}
			</div>
		</div> <!-- row.// -->
	</div><!-- container // -->
</section>
@endif


@if (@$categories)
<section class="section-request mt-4">
	<div class="container">
		<!-- New Product -->
		<header class="section-heading heading-line1">
			<h4 class="title-section bg1 text-uppercase font-weight-bold m-0">{{__('frontend.category')}}</h4>
		</header>
		<div class="row-sm">
			<div class="owl-carousel owl-theme">
				@foreach ($categories as $key => $value)
				<div class="px-1"> 
					<div class="row">
							<div class="col-lg-12 col-md-6 col-sm-4 mx-auto text-center">
								<a href="{{ route('search.category', ['id'=> ($value['id'])]) }}">
									<div class="card">
										<div class="d-flex justify-content-center">
											<img   src="{{ $value['image_name'] ?? 'https://bitsofco.de/content/images/2018/12/Screenshot-2018-12-16-at-21.06.29.png' }}" style="margin:0; height: 10em !important;width: 10em !important;" class="img-fluid w-100 mr-0 product-image">
										</div>
										<div class="card-body p-3">
											<h5 class="title text-center">
													{{ $value['default_name'] }}
											</h5>
										</div>
									</div>
								</a>
							</div>
					</div>
				</div>
				@endforeach
			</div>
		</div> <!-- row.// -->
	</div><!-- container // -->
</section>
@endif


<script>
	$(document).ready(function(){
		$(".owl-carousel").owlCarousel({
			items:4,
			loop:true,
			margin:10,
			// autoplay:true,
			// stagePadding: 50,
			nav:true,
			// autoplayTimeout:1500,
			// autoplayHoverPause:true,
			// responsiveClass:true,
			responsive:{
			0:{
				items:1,
				// nav:true
			},
			600:{
				items:1,
				// nav:true
			},
			1000:{
				items:4,
				// nav:false,
				// loop:true
			}
		}
		});
	});

	$(document).ready(function () {
		$(".dl-cart").hide();

		$(".add-to-cart").click(function (e) {
			e.preventDefault();
			let pid = $(this).attr("id");
			$(this).hide();
			var ele = $(this)
				.closest("div")
				.find(".dl-cart")
				.addClass("d-inline")
				.show();
			// $("#".pid).removeClass("invisible").addClass("visible");
			$.ajaxSetup({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
			});
			$.ajax({
				type: "POST",
				url: "/add-cart",
				data: { id: pid },
				success: function (data) {
					$(".cart-qty").html(data.data);
					// console.log($(".cart-qty").text());
				},
			});
		});
	});

	$()
</script>
@endsection
