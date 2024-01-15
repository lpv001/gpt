@extends('frontend::layouts.main')

@section('styles')
	<style>
		dt {
			font-weight: 500;
		}
		
	</style>
@endsection

@section('content')
<!-- ========================= SECTION ITEMS ========================= -->
<section class="section-request padding-y-sm">
<div class="container-fluid px-5">

<div class="row search-page">
	<aside class="col-sm-3">
	@if (@$related_categories)
		<div class="card card-filter mb-3">
			<article class="card-group-item mt-1">
				<div class="filter-content collapse show" id="collapse22">
					<div class="card-body ">
						<span class="mr-3"><i class="fa fa-list-ul" aria-hidden="true"></i></span>
						<strong>{{ __('frontend.categories') }}</strong>
						<div class="category-list-group" >
							@foreach ($related_categories as $item)
								<dl class="cl-item mb-0 py-1">
									<dt class="px-0">
										<span>
											<img class="mr-2" style="width: 20%" src="{{ $item['image_name'] }}" alt="">
										</span>
										<a href="{{ route('search.category', $item['id']) }}">{{$item['default_name']}}</a>
									</dt>
								</dl>
							@endforeach
						</div>
					</div> <!-- card-body.// -->
				</div> <!-- collapse .// -->
			</article> <!-- card-group-item.// -->
		</div>
	@endif

	@if (@$brands)
		<div class="card card-filter mb-3">
			<article class="card-group-item mt-1">
				<div class="filter-content collapse show" id="collapse22">
					<div class="card-body ">
						<span class="mr-3"><i class="fa fa-list-ul" aria-hidden="true"></i></span>
						<strong>{{ __('frontend.brands') }}</strong> 
						{{-- <h6 class="mt-1" style="font-weight: 600">{{$category_name ?? ''}}</h6> --}}
						<div class="category-list-group" >
							@foreach ($brands as $item)
								<dl class="cl-item mb-0 py-1">
									<dt class="px-0">
										<span>
											<img class="mr-2" style="width: 20%" src="{{ $item['image_name'] }}" alt="">
										</span>
										<a href="{{ route('search.brand', $item['id']) }}">{{$item['name']}}</a>
									</dt>
								</dl>
							@endforeach
						</div>
					</div> <!-- card-body.// -->
				</div> <!-- collapse .// -->
			</article> <!-- card-group-item.// -->
		</div>
	@endif

	<div class="card card-filter py-4">
	<article class="card-group-item">
		<header class="card-header px-4">
			<a href="#" data-toggle="collapse" data-target="#collapse33">
				<i class="icon-action fa fa-chevron-down"></i>
				<h6 class="bg-white">{{__('frontend.by_price')}} </h6>
			</a>
		</header>
		<div class="filter-content collapse show" id="collapse33">
			<div class="card-body">
				<form action="{{ route('search') }}" method="GET">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label>{{__('frontend.minimun')}}</label>
							<input class="form-control" name="min_price" value="{{ Request::get('min_price') }}" placeholder="$0" type="number">
						</div>
						<div class="form-group text-right col-md-6">
							<label>{{__('frontend.maximum')}}</label>
							<input class="form-control" name="max_price" value="{{ Request::get('max_price') }}" placeholder="$100" type="number">
						</div>
						</div> <!-- form-row.// -->
						<input type="hidden" name="q" value="{{ Request::get('q') }}">
						<button class="btn btn-warning btn-block ">{{__('frontend.apply')}}</button>
				</form>
				
			</div> <!-- card-body.// -->
		</div> <!-- collapse .// -->
	</article> <!-- card-group-item.// -->
	</div> <!-- card.// -->
</aside> <!-- col.// -->


	<main class="col-sm-9">
		<div class="card p-4">
				@if (count($products) > 0)
				<div class="d-flex justify-content-start flex-wrap product-page" id="pLists">
				
					@foreach ($products as $key => $product)
						@include('frontend::components.product_medium', ['product' => $product])
						{{-- <div class="mr-2 my-4 shadow-sm product-card">
							<a href="{{route('product.show', $product['id'])}}">
								<img 
								class="card-img-top product-image" 
								src="{{$product['image_name'] ?? 'https://oscar-website-assets-production.s3.amazonaws.com/assets/camaleon_cms/image-not-found-4a963b95bf081c3ea02923dceaeb3f8085e1a654fc54840aac61a57a60903fef.png'}}" 
								alt="product/image">
							</a>

							<div class="my-2 p-2">
								<p class="title">{{$product['name']}}</p>

								<div class="d-flex justify-content-between align-items-center">
									<small href="#" class="text-secondary font-weight-bold shop-info" data-shop="{{$product['shop_id']}}">{{ $product['shop_name'] }}</small>
									<span class="m-0 text-danger font-weight-bold" style="">USD ${{ $product['unit_price'] }}</span>
								</div>
							</div>
						</div> --}}
					@endforeach
					<div class="mt-2 mx-auto spinner">
						<img src="{{ asset('img/circle-spinner.gif') }}" alt="">
					</div>
				</div>
				@else
						<h6 class="text-center m-2">Products not found!</h6>
				@endif
		</div>
	</main>

	</div>
</div> <!-- row.// -->


</div><!-- container // -->
</section>

<script src="{{  asset('/js/product-pagination.js') }}"></script>
<script>
	$(document).ready(function() {
		// setTimeout(() => {
		// 	let catLists = $(".category-list-group").clone();
		// 	$(".header-cat").append(catLists);
		// }, 2000);

		$('input[name=min_price]').on('keyup', function() {
			let val = $(this).val();
			console.log(val)
			$('input[name=min_price]').val(val)
		});

		$('input[name=max_price]').on('keyup', function() {
			let val = $(this).val();
			console.log(val)
			$('input[name=max_price]').val(val)
		})
	});
</script>
@endsection
