@extends('frontend::layouts.main')

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
					<header class="card-header">
					<h6 class="title">{{__('frontend.profile')}}</h6>
				</header>

				<div class="card-body">
					<div class="row">
						<div class="col-md-3">

							<div class="form-group">
								<img src="https://cdn.business2community.com/wp-content/uploads/2017/08/blank-profile-picture-973460_640.png" width="80%" height="80%" alt="">
							</div>
						</div>

						<div class="col-md-6">
							<form class="shipping-form">
								<div class="form-group">
									<label for="name">{{__('frontend.name')}} :</label>
									<input type="text" placeholder="Full name" class="form-control" value="{{ auth()->user() ? auth()->user()->full_name : '' }}">
								</div>

								<div class="form-group">
									<label for="phone">{{__('frontend.phone')}} :</label>
									<input type="text" placeholder="Phone" class="form-control" value="{{ auth()->user() ? auth()->user()->phone : '' }}">
								</div>

								{{-- <div class="form-group row">
									<label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
									<div class="col-sm-10">
									  <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
									</div>
								  </div> --}}

								<div class="text-right">
									<a href="#" class="btn btn-warning">{{__('frontend.save')}}</a>
								</div>
							</form>
						</div>
					</div>

				</div>
			</div>
		</div> <!-- col // -->
		</div>
	</main>

</div>
</div> <!-- row.// -->


</div><!-- container // -->
</section>

@endsection
