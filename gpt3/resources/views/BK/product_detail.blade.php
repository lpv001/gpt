@extends('layouts.main')

@section('content')


<section class="section-content padding-y">
<div class="container">


<div class="row">
<div class="col-lg-12">

	<div class="card">
		<div class="row no-gutters">
			<aside class="col-sm-5 border-right">
	<article class="gallery-wrap">
	<div class="img-big-wrap">
	  <div> <a href="img/items/Freshy-Apple-Juice.jpg" data-fancybox=""><img src="img/items/Freshy-Apple-Juice.jpg"></a></div>
	</div> <!-- slider-product.// -->
	<div class="img-small-wrap">
	  <div class="item-gallery"> <img src="img/items/Freshy-Apple-Juice.jpg"></div>
	  <div class="item-gallery"> <img src="img/items/Juice-Freshy-Apple-Juice-Front.jpg"></div>
{{--}}
		<div class="item-gallery"> <img src="ui-ecommerce/images/items/3.jpg"></div>
	  <div class="item-gallery"> <img src="ui-ecommerce/images/items/4.jpg"></div>
		--}}
	</div> <!-- slider-nav.// -->
	</article> <!-- gallery-wrap .end// -->
			</aside>
			<aside class="col-sm-7">
	<article class="p-5">
		<h3 class="title mb-3">Freshy Apple Juice</h3>

	<div class="mb-3">
		<var class="price h3 text-warning">
			<span class="currency">US $</span><span class="num">1.00</span>
		</var>
		<span>/per can</span>
	</div> <!-- price-detail-wrap .// -->
	<dl>
	  <dt>Description</dt>
	  <dd><p>Here goes description consectetur adipisicing elit, sed do eiusmod
	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
	quis nostrud exercitation ullamco </p></dd>
	</dl>
	<dl class="row">
	  <dt class="col-sm-3">Unit</dt>
	  <dd class="col-sm-9">250ml</dd>
		{{--}}
	  <dt class="col-sm-3">Color</dt>
	  <dd class="col-sm-9">Black and white </dd>

	  <dt class="col-sm-3">Delivery</dt>
	  <dd class="col-sm-9">Russia, USA, and Europe </dd>
		--}}


	</dl>

	{{--}}
	<div class="rating-wrap">

		<ul class="rating-stars">
			<li style="width:80%" class="stars-active">
				<i class="fa fa-star"></i> <i class="fa fa-star"></i>
				<i class="fa fa-star"></i> <i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
			</li>
			<li>
				<i class="fa fa-star"></i> <i class="fa fa-star"></i>
				<i class="fa fa-star"></i> <i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
			</li>
		</ul>


		<div class="label-rating">132 reviews</div>
		<div class="label-rating">154 orders </div>


	</div> <!-- rating-wrap.// -->
	--}}


	<hr>
		<div class="row">
			<div class="col-sm-5">
				<dl class="dlist-inline">
				  <dt>Quantity: </dt>
				  <dd>
				  	<select class="form-control form-control-sm" style="width:70px;">
				  		<option> 1 </option>
				  		<option> 2 </option>
				  		<option> 3 </option>
				  	</select>
				  </dd>
				</dl>  <!-- item-property .// -->
			</div> <!-- col.// -->


			{{--}}
			<div class="col-sm-7">
				<dl class="dlist-inline">
					  <dt>Size: </dt>
					  <dd>
					  	<label class="form-check form-check-inline">
						  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
						  <span class="form-check-label">SM</span>
						</label>
						<label class="form-check form-check-inline">
						  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
						  <span class="form-check-label">MD</span>
						</label>
						<label class="form-check form-check-inline">
						  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
						  <span class="form-check-label">XXL</span>
						</label>
					  </dd>
				</dl>  <!-- item-property .// -->
			</div> <!-- col.// -->
			--}}


		</div> <!-- row.// -->
		<hr>
		{{--}}<a href="#" class="btn  btn-primary"> Buy now </a>--}}
		<a href="#" class="btn  btn-warning"> <i class="fas fa-shopping-cart"></i> Add to cart </a>
	</article> <!-- card-body.// -->
			</aside> <!-- col.// -->
		</div> <!-- row.// -->
	</div> <!-- card.// -->


</div>
</div> <!-- row.// -->


</div><!-- container // -->
</section>



@endsection
