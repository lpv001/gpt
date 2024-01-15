@extends('frontend::layouts.main')

@section('content')

<style>
	.user-profile {
		width: 105px;
		height: 100px;
		border-radius: 50%;
	}

</style>

<section class="section-request padding-y-sm">
	<div class="container">
		<div class="row">
		
			<aside class="col-sm-3 rounded left-side mb-4">
				{{-- <div class="card p-3"> --}}
					@include('frontend::my.partials.sidebar_menu')
				{{-- </div> --}}
			</aside>

			<main class="col-sm-8 right-side">
				<div class="card p-3">
					<div class="row p-2">
						<div class="col-md-12">
							<h5 class="title font-weight-bold">{{ __('frontend.personal_information') }}</h5>
							<hr class="my-3">
							<div class="d-flex p-3">
								<div class="">
									<img src="https://i.stack.imgur.com/l60Hf.png" class="user-profile" alt="">
								</div>

								<div class="flex-grow-1 px-4">
									<div class="d-flex justify-content-between">
										<div class="">
											<p class="mb-1">{{ __('frontend.name') }}: <span>{{ auth()->user()->full_name }}</span></p>
											<p class="mb-1">{{ __('frontend.phone') }}: <span>{{ auth()->user()->phone }}</span></p>
											<!--<p class="mb-1">{{ __('fronend.membership_type') }}: <span></span></p>-->
										</div>
										
										{{--
										<div class="">
											<a href="#" class="btn btn-warning btn-sm">{{ __('frontend.edit_profile') }}</a>
										</div>
										--}}
									</div>
								</div>
							</div>
						</div> <!-- col // -->
					</div>
<!--
					<hr>
					<div class="row p-2">
						<div class="col-sm">
							<h5 class="title">{{ __('frontend.my_orders') }}</h5>

							<div class="mt-4 d-flex px-4 justify-content-between">

								<div class="text-center">
									<h4 class="mb-1">0</h4>
									<span>{{ __('frontend.all_orders') }}</span>
								</div>

								<div class="text-center">
									<h4 class="mb-1">0</h4>
									<span>{{ __('frontend.pending_orders') }}</span>
								</div>
								<div class="text-center">
									<h4 class="mb-1">0</h4>
									<span>{{ __('frontend.delivered_orders') }}</span>
								</div>

								<div class="text-center">
									<h4 class="mb-1">0</h4>
									<span>{{ __('frontend.completed_orders') }}</span>
								</div>
							</div>
						</div>
						{{-- <div class="col-sm">

						</div> --}}
					</div>
-->
				</div>
				
			</main>
		</div> <!-- row.// -->
	</div><!-- container // -->
</section>

@endsection
