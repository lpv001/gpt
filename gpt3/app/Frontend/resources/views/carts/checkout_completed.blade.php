@extends('frontend::layouts.main')

@section('content')


<section class="section-content padding-y">
<div class="container">


	<div class="row">
		<main class="col-sm-12">


<div class="card">
  <div class="card-body text-center">
    <h5 class="card-title">{{__('frontend.thank_you_for_your_order')}}</h5>
	<i class="bi bi-check-circle"></i>
    <p class="card-text">
		{{__('frontend.your_order_number')}}: {{@$order_id ?? ''}}
		</p>
    <a href="{{route('orders.show', @$order_id)}}" class="btn btn-warning">{{__('frontend.view_your_order')}}</a>
		<a href="{{ route('search')}}" class="btn btn-warning">{{__('frontend.continue_shopping')}}</a>
  </div>
</div> <!-- card.// -->



	</main> <!-- col.// -->

	</div>

</div><!-- container // -->
</section>



@endsection
