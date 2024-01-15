@extends('layouts.main')

@section('content')

<!-- ========================= SECTION MAIN ========================= -->
<br>
<section class="section-main bg1 padding-bottom">
<div class="container">
<div class="row no-gutters bAdd to cart bAdd to cart-top-0 bg-white">

{{--}}

<aside class="col-lg-5-24">
<nav>
	<div class="title-category bg-secondary white d-none d-lg-block" style="margin-top:-53px">
		<span>Categories</span>
	</div>
	<ul class="menu-category">
		<li> <a href="#">Beer </a></li>
		<li> <a href="#">Juice & Juice Drinks </a></li>
		<li> <a href="#">Energy Drinks </a></li>
		<li> <a href="#">Sports Drinks  </a></li>
		<li> <a href="#">Drinking Water  </a></li>


		<li> <a href="#">Mobile phones  </a></li>
		<li class="has-submenu"> <a href="#">More category  <i class="icon-arrow-right pull-right"></i></a>
			<ul class="submenu">
				<li> <a href="#">Food &amp Beverage </a></li>
				<li> <a href="#">Home Equipments </a></li>
				<li> <a href="#">Machinery Items </a></li>
				<li> <a href="#">Toys & Hobbies  </a></li>
				<li> <a href="#">Consumer Electronics  </a></li>
				<li> <a href="#">Home & Garden  </a></li>
				<li> <a href="#">Beauty & Personal Care  </a></li>
			</ul>
		</li>


	</ul>
</nav>
</aside> <!-- col.// -->
--}}

<main class="col-lg-24-24">
<!-- ========= intro aside ========= -->
<div class="row no-gutters">
	<div class="col-lg-12 col-md-12">
<!-- ================= main slide ================= -->

<div class="owl-init slider-main owl-carousel" data-items="1" data-margin="1" data-nav="true" data-dots="false">
	<div class="item-slide">
		<img src="img/banners/banner1.jpg">
	</div>
	<div class="item-slide">
		<img src="img/banners/banner2.jpg">
	</div>
  {{--}}
	<div class="item-slide">
		<img src="ui-ecommerce/images/banners/slide3.jpg">
	</div>
  --}}
</div>

<!-- ============== main slidesow .end // ============= -->
	</div> <!-- col.// -->

  {{--}}
	<div class="col-lg-3 col-md-4">
<ul class="list-group list-group-flush">
    <li class="list-group-item">
		<h6>Group of items goes here</h6>
		<a href="#" class="btn btn-warning btn-sm btn-round"> View all </a>
    </li>
    <li class="list-group-item">
		<h6>Group of items goes here</h6>
		<a href="#" class="btn btn-warning btn-sm btn-round"> View all </a>
    </li>
    <li class="list-group-item">
		<h6>Group of items goes here</h6>
		<a href="#" class="btn btn-warning btn-sm btn-round"> View all </a>
    </li>
  </ul>
	</div> <!-- col.// -->
  --}}


</div> <!-- row.// -->
<!-- ======== intro aside ========= .// -->
</main> <!-- col.// -->
</div> <!-- row.// -->
</div> <!-- container .//  -->
</section>
<!-- ========================= SECTION MAIN END// ========================= -->





<!-- ========================= SECTION ITEMS ========================= -->
<section class="section-request padding-y-sm">
<div class="container">
<header class="section-heading heading-line1">
	<h4 class="title-section bg1 text-uppercase">Recommended items</h4>
</header>

<div class="row-sm">
<div class="col-md-3">
	<figure class="card card-product">
    <span class="badge-new"> NEW </span>
    <span class="badge-offer"><b> -50%</b></span>
		<a href="{{ route('product-detail') }}">
			<div class="img-wrap"> <img src="img/items/Freshy-Apple-Juice.jpg"></div>
		</a>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="{{ route('product-detail') }}">Good item name</a></h6>

			<div class="price-wrap h5">
        <a href="/cart" class="btn btn-warning btn-sm float-right"> Add to cart </a>
				<span class="price-new">$1280</span>
				<del class="price-old">$1980</del>
			</div> <!-- price-wrap.// -->

		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->
<div class="col-md-3">
	<figure class="card card-product">
		<div class="img-wrap"> <img src="img/items/ganzberg_beer.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">The name of product</a></h6>
			<div class="price-wrap">
        <a href="#" class="btn btn-warning btn-sm float-right"> Add to cart </a>
				<span class="price-new">$280</span>
			</div> <!-- price-wrap.// -->
		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->
<div class="col-md-3">
	<figure class="card card-product">
		<div class="img-wrap"> <img src="img/items/Freshy-Soybean.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">Name of product</a></h6>
			<div class="price-wrap">
        <a href="#" class="btn btn-warning btn-sm float-right"> Add to cart </a>
				<span class="price-new">$280</span>
			</div> <!-- price-wrap.// -->
		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->
<div class="col-md-3">
	<figure class="card card-product">
		<div class="img-wrap"> <img src="img/items/ganzberg_beer.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">The name of product</a></h6>
			<div class="price-wrap">
        <a href="#" class="btn btn-warning btn-sm float-right"> Add to cart </a>
				<span class="price-new">$280</span>
			</div> <!-- price-wrap.// -->
		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->



<div class="col-md-3">
	<figure class="card card-product">
		<div class="img-wrap"> <img src="img/items/ganzberg_beer.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">The name of product</a></h6>
			<div class="price-wrap">
        <a href="#" class="btn btn-warning btn-sm float-right"> Add to cart </a>
				<span class="price-new">$280</span>
			</div> <!-- price-wrap.// -->
		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->
<div class="col-md-3">
	<figure class="card card-product">
    <span class="badge-new"> NEW </span>
    <span class="badge-offer"><b> - 50%</b></span>
		<div class="img-wrap"> <img src="img/items/Freshy-Apple-Juice.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">Good item name</a></h6>

			<div class="price-wrap h5">
        <a href="#" class="btn btn-warning btn-sm float-right"> Add to cart </a>
				<span class="price-new">$1280</span>
				<del class="price-old">$1980</del>
			</div> <!-- price-wrap.// -->

		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->
<div class="col-md-3">
	<figure class="card card-product">
		<div class="img-wrap"> <img src="img/items/ganzberg_beer.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">The name of product</a></h6>
			<div class="price-wrap">
        <a href="#" class="btn btn-warning btn-sm float-right"> Add to cart </a>
				<span class="price-new">$280</span>
			</div> <!-- price-wrap.// -->
		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->
<div class="col-md-3">
	<figure class="card card-product">
		<div class="img-wrap"> <img src="img/items/Freshy-Soybean.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">Name of product</a></h6>
			<div class="price-wrap">
        <a href="#" class="btn btn-warning btn-sm float-right"> Add to cart </a>
				<span class="price-new">$280</span>
			</div> <!-- price-wrap.// -->
		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->



</div> <!-- row.// -->


</div><!-- container // -->
</section>
<!-- ========================= SECTION ITEMS .END// ========================= -->



{{--}}

<!-- ========================= SECTION CONTENT ========================= -->
<section class="section-content padding-y">
<div class="container">


<header class="section-heading">
<h3 class="title-section">Main section is here</h3>
</header>

<article>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>


</article>


</div> <!-- container .//  -->
</section>
<!-- ========================= SECTION CONTENT END// ========================= -->
--}}


@endsection
