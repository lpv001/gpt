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
						<h6 class="title text-left">{{__('frontend.member_request')}}</h6>
					</div>
				</div>
				
			</header>
		</div>


  	<div class="card-body px-0">
			<div class="row mx-4">
				<div class="col-sm">
					<div class="row">
						<div class="col-sm text-center">
                            <h4 class="text-uppercase">Our Operation is maintaining</h4>
						</div>
					</div>
					
				</div>
			</div>
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
		


	</script>	
@endsection
