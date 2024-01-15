@extends('frontend::layouts.main')

@section('content')
<br>
<section class="section-request padding-y-sm">
<div class="container">

<div class="row">
	<aside class="col-sm-3 left-side mb-4">
	 	@include('frontend::my.partials.sidebar_menu')
	</aside> <!-- col.// -->


<main class="col-sm-9 right-side ">
<div class="row">

<div class="col-md-12">


	<div class="card">
		<div class="pull-left">
			<header class="card-header">
				<div class="row">
					<div class="col-sm">
						<h6 class="title text-left">{{__('frontend.new_product')}}</h6>
					</div>				
				</div>
				
			</header>
		</div>


  <div class="card-body px-0">


	<div class="container">
		
		{{-- {!! Form::model($product, ['route' => ['products.update', $product->id], 'method' => 'put', 'enctype'=>'multipart/form-data']) !!} --}}
		{!! Form::open(['route' => ['my-products.update', $product->id], 'enctype'=>'multipart/form-data']) !!}
			{!! Form::hidden("_method", "PUT") !!}
			@include('frontend::my.account.products.fields')
		{!! Form::close() !!}
	
	</div> <!-- end container // -->
</div>




</div> <!-- col // -->
</div></main>

</div>
</div> <!-- row.// -->
</div><!-- container // -->
</section>

@endsection


