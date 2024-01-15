<div class="container item-shop card bg-white my-4 rounded p-3">
	<div class="row">
		<div class="col-3 d-flex">
			<div class="mx-auto text-center">
				<h4 class="">{{ __('frontend.shop_title', ['shop_name' => $shop['name']]) }}</h4>
				<div class="my-2">
					<img width="50%" src="{{ $shop['logo_image'] }}" alt="logo">
					<div>
						<span>
							<i class="fa fa-star text-warning" aria-hidden="true"></i>
							<i class="fa fa-star text-warning" aria-hidden="true"></i>
							<i class="fa fa-star text-warning" aria-hidden="true"></i>
							<i class="fa fa-star text-warning" aria-hidden="true"></i>
							<i class="fa fa-star text-warning" aria-hidden="true"></i>
						</span>
					</div>
				</div>
				<div class="my-2">
					<a href="#" class="btn btn-sm btn-warning px-5">{{ __('frontend.visit_shop') }}</a>
				</div>
			</div>
		</div>

		<div class="p-4 mx-auto">
			<!--<div><h3>{{ __('frontend.more_products') }}</h3></div>-->
			<div class="d-flex d-flex flex-wrap more-product-lists" style="width: 100% !important;">
				@foreach ($seller_products as $item)
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
</div>
