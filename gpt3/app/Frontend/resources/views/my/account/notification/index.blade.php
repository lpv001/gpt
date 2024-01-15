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
		<div class="pull-left">
			<header class="card-header">
				<div class="row">
					<div class="col-sm">
						<h6 class="title text-left">{{__('frontend.notification')}}</h6>
					</div>
				</div>
				
			</header>
		</div>


  	<div class="card-body px-0">
        @if (count(@$data) > 0)

		@foreach (@$data as $item)
		{{-- {{dd($order)}} --}}
            <div class="list-group mb-3">
                <a  class="list-group-item list-group-item-action " href="">
                    <h6>{{$item['title']}}</h6>
                </a>
            </div>
		@endforeach
	@else
		<div class="row">
			<div class="col-sm text-secondary text-center">No order</div>
		</div>
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
		


	</script>	
@endsection
