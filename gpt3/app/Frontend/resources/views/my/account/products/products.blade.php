@if (@$products)
		@foreach ($products as $item)
		{{-- {{dd($item)}} --}}
			<div class="row mb-3 product">
				<div class="col-sm-7 d-flex flex-row">
					<div class="col-sm-3">
						@if (count($item['images']) > 0)
							<img src="{{ asset('/uploads/images/products/'. $item['images'][0]['image_name']) }}"
							alt="No product" 
							width="100%" 
							class="img-thumbnail">
						@endif
					</div>
					<div class="col-sm-9">
						<h5 class="mb-3">{{ $item['name']}} Code</h5>
						<dd>
							<p class="m-0" style="font-size: 14px">{{__('frontend.code')}}: {{$item['product_code']}}</p>
							<p class="m-0" style="font-size: 14px">{{__('frontend.category')}}: {{ $item['category_name'] }}</p>
							<p class="m-0" style="font-size: 14px">{{__('frontend.brands')}}: {{ $item['brand_name']}}</p>
						</dd>
					</div>
					
				</div>
				<div class="col-sm-5">
					<div class="text-right p-3">
						{{-- <a href="#" class="btn btn-sm btn-warning text-uppercase"><i class="fa fa-eye" aria-hidden="true"></i></a> --}}
						<a href="{{ route('my-products.edit', $item['id']) }}" class="btn btn-sm btn-primary text-uppercase"><i class="fas fa-edit"></i></a>
						<a href="#" data-id="{{ $item['id'] }}" class="btn btn-sm btn-danger text-uppercase delete"><i class="fa fa-trash text-white" aria-hidden="true"></i></a>
					</div>
				</div>
			
			</div>
        @endforeach
@endif