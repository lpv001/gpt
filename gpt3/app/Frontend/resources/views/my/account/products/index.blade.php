@extends('frontend::layouts.main')

@section('content')
<br>
<section class="section-request padding-y-sm">
<div class="container">

{{--}}
<header class="section-heading heading-line1">
	<h4 class="title-section bg1 text-uppercase">Recommended items</h4>
</header>
--}}

<div class="row">
	<aside class="col-sm-3 left-side mb-4">
	 	@include('frontend::my.partials.sidebar_menu')
	</aside> <!-- col.// -->


<main class="col-sm-9 right-side">
<div class="row">

<div class="col-md-12">


	<div class="card">
		<div class="pull-left">
			<header class="card-header">
				<div class="row">
					<div class="col-sm">
						<h6 class="title text-left">{{__('frontend.my_product')}}</h6>
					</div>
					<div class="col-sm text-right">
						<a href="{{ route('myaccount.add_product') }}" style="background-color: #fd7e14 !important; border:none" class="btn btn-sm btn-primary ">New Product</a>
					</div>
					
				</div>
				
			</header>
		</div>


  <div class="card-body px-0">
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
						<h5 class="mb-3">{{ $item['name']}}</h5>
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
</div>
</div>




</div> <!-- col // -->
</div></main>

</div>
</div> <!-- row.// -->
</div><!-- container // -->
</section>

@endsection

@section('scripts')
	<script>
		$(document).ready(function(){
			var position = $(window).scrollTop(); 
			height  = $(window).height();
			var	page = 1;

			// should start at 0
			$(window).scroll(function() {
				var scroll = $(window).scrollTop();
				if(scroll > position) { // scroll down
					if (position  >= height){
						height += height;
						page += 1;
						lastElement = $('.product').length - 1;
						element = '';

						$.get('{{ route('my-products.index') }}', { page }, function(response) {
							div = $('.product')[lastElement];
							$(div).after(response.data)
						});
					}
				} 
				position = scroll;
			});
		});

		$(document).on('click', '.delete', function() {
			$(this).closest('.row')[0].remove();

			$.ajax({
				url: `my-products/${$(this).data('id')}` ,
				type: 'DELETE',
				data: {
					_token: '{{ csrf_token() }}'
				},
				success: function(response) {
					console.log(response)
				}
			});
		});
	</script>	
@endsection
