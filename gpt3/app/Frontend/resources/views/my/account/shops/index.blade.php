@extends('frontend::layouts.main')

@section('styles')
	<style>
		#map {
			height: 300px;
		}
		img.shop-images {
			width: 100px;
			height: 100px;
		}
	</style>
@endsection

@section('content')
<br>
<section class="section-request padding-y-sm">
	<div class="container">
		<div class="row">
			<aside class="col-sm-3 left-side mb-4">
				@include('frontend::my.partials.sidebar_menu')
			</aside> <!-- col.// -->


			<main class="col-sm-9 right-side">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="pull-left px-4 pt-3">
								<h5 class="title text-left">{{ __('frontend.shop_info') }}</h5>

								@if (@$message)
									<div class="alert alert-warning alert-dismissible fade show" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
										<ul class="p-0 m-0" style="list-style: none;">
											<li>{{$message}}</li>
										</ul>
									</div>
								@endif
								
								<hr>

								@if($errors->any())
									<div class="alert alert-danger alert-dismissible fade show" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
										<ul class="p-0 m-0" style="list-style: none;">
											<li>{{$errors->first()}}</li>
										</ul>
									</div>
								@endif

								@if (session('success'))
									<div class="alert alert-success">
										{{ session('success') }}
									</div>
								@endif
							</div>

							<div class="card-body px-4">
								<form action="{{ route('shop.store') }}" method="post" enctype="multipart/form-data">
									@csrf
									@include('frontend::my.account.shops.create')
								</form>
							</div>
						</div>
					</div> <!-- col // -->
				</div>
			</main>
		</div> <!-- row.// -->
	</div><!-- container // -->
</section>
<script
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJDGDxEhNyb4rND-X7cKaYvcr0zLPYMGs&callback=initMap&libraries=&v=weekly&language=km"
		defer>
	</script>
@endsection

@section('scripts')
	<script>
		var isLoadCurrentMap = {!! @$shop ? 1 : 0 !!};
		const lat = "{{ @$shop['lat'] }}";
		const lng = "{{ @$shop['lng'] }}";
		const shopAddress = "{{ @$shop['address'] }}";
	</script>
	<script src="{{ asset('js/frontend/open_shop.js') }}"></script>
@endsection
