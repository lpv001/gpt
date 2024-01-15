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
						<h6 class="title text-left">{{__('frontend.customer_order')}}</h6>
					</div>
				</div>
				
			</header>
		</div>


  	<div class="card-body px-0">
		  <div class="list-group">
			@if (count(@$orders) > 0)
				
				@foreach ($orders as $item)
				<a href="{{ route('customer-order.show', $item['id']) }}">
					<div class="row mx-2 list-group-item list-group-item-action p-0 mb-2" style="width: 98%;">
						
						<div class="col-sm">
							<div class="row">
								<div class="col-sm p-3">
									<div class="row">
										<div class="col-sm d-flex flex-row">
											{{-- <i class="fa fa-shopping-cart" aria-hidden="true" style="font-size: 35px"></i> --}}
											<img 
												src="https://static.thenounproject.com/png/832921-200.png" 
												alt="img" 
												width="25%" 
												class="img-thumbnail mr-3"
												style="border: none">
											<div class="">
												<p class="mb-0">{{$item['user_name']}}</p>
												<p class="mb-0">{{$item['address_phone']}}</p>
												<p class="mb-0"><span class="badge badge-pill badge-warning text-white text-uppercase">#{{$item['id']}} : {{$status[$item['order_status_id']]}}</span></p>
											</div>
										</div>
										<div class="col-sm text-right mr-3">
												<p class="mb-0 text-success">{{ $item['total'] }} $</p>
												<p class="mb-0"> {{$item['delivery_pickup_date']}}</p>
												<small class="text-danger">{{ $item['note'] }}</small>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</a>
				@endforeach

				{{-- <div class="row mx-2">
					<nav aria-label="Page navigation example">
						<ul class="pagination">
						  <li class="page-item"><a class="page-link" href="">Previous</a></li>
						  <li class="page-item"><a class="page-link active text-white" href="">1</a></li>
						  <li class="page-item"><a class="page-link" href="">2</a></li>
						  <li class="page-item"><a class="page-link" href="">3</a></li>
						  <li class="page-item"><a class="page-link" href="">Next</a></li>
						</ul>
					  </nav>
				</div> --}}
				
			@endif
			
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
