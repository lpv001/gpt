<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="Bootstrap-ecommerce by Vosidiy">

<title>CHABHUOY Online Shopping</title>

<link rel="shortcut icon" type="image/x-icon" href="ui-ecommerce/images/favicon.ico">

<!-- jQuery -->
<script src="ui-ecommerce/js/jquery-2.0.0.min.js" type="text/javascript"></script>

<!-- Bootstrap4 files-->
<script src="ui-ecommerce/js/bootstrap.bundle.min.js" type="text/javascript"></script>
<link href="ui-ecommerce/css/bootstrap.css" rel="stylesheet" type="text/css"/>

<!-- Font awesome 5 -->
<link href="ui-ecommerce/fonts/fontawesome/css/fontawesome-all.min.css" type="text/css" rel="stylesheet">

<!-- plugin: owl carousel  -->
<link href="ui-ecommerce/plugins/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="ui-ecommerce/plugins/owlcarousel/assets/owl.theme.default.css" rel="stylesheet">
<script src="ui-ecommerce/plugins/owlcarousel/owl.carousel.min.js"></script>

<!-- custom style -->
<link href="ui-ecommerce/css/ui.css" rel="stylesheet" type="text/css"/>
<link href="ui-ecommerce/css/responsive.css" rel="stylesheet" media="only screen and (max-width: 1200px)" />

<!-- custom javascript -->
<script src="ui-ecommerce/js/script.js" type="text/javascript"></script>

<script type="text/javascript">
/// some script

// jquery ready start
$(document).ready(function() {
	// jQuery code

});
// jquery end
</script>

</head>
<body>
<header class="section-header">
<nav class="navbar navbar-top navbar-expand-lg navbar-dark bg-secondary">
<div class="container">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTop" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarTop">
    <ul class="navbar-nav mr-auto">
		<li class="nav-item active">
			<a class="nav-link" href="#"> <i class="fa fa-map-marker-alt"></i> Deliver to: Phnom Penh </a>
		</li>
    {{--}}
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"> USD </a>
			<ul class="dropdown-menu small">
				<li><a class="dropdown-item" href="#">EUR</a></li>
				<li><a class="dropdown-item" href="#">AED</a></li>
				<li><a class="dropdown-item" href="#">RUBL </a></li>
		    </ul>
		</li>
    --}}
		 <li class="nav-item dropdown">
		 	<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">   Language </a>
		    <ul class="dropdown-menu small">
				<li><a class="dropdown-item" href="#">English</a></li>
				<li><a class="dropdown-item" href="#">Khmer</a></li>
				</ul>
		</li>
    </ul>
    <ul class="navbar-nav">
		<li><a href="#" class="nav-link"> My Account </a></li>
		<li><a href="#" class="nav-link"> Wishlist </a></li>
		<li><a href="#" class="nav-link"> Checkout </a></li>
	</ul> <!-- list-inline //  -->
  </div> <!-- navbar-collapse .// -->
</div> <!-- container //  -->
</nav>

<section class="header-main">
	<div class="container">
<div class="row align-items-center">
	<div class="col-lg-5-24 col-sm-5 col-4">
		<div class="brand-wrap">
			<img class="logo" src="ui-ecommerce/images/logo-dark.png">
			<h2 class="logo-text">CHABHUOY Online Shopping</h2>
		</div> <!-- brand-wrap.// -->
	</div>
	<div class="col-lg-13-24 col-sm-12 order-3 order-lg-2">
		<form action="#">
			<div class="input-group w-100">
				<select class="custom-select"  name="category_name">
						<option value="">All type</option><option value="codex">Special</option>
						<option value="comments">Only best</option>
						<option value="content">Latest</option>
				</select>
			    <input type="text" class="form-control" style="width:60%;" placeholder="Search">

			    <div class="input-group-append">
			      <button class="btn btn-primary" type="submit">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		    </div>
		</form> <!-- search-wrap .end// -->
	</div> <!-- col.// -->
	<div class="col-lg-6-24 col-sm-7 col-8  order-2  order-lg-3">
		<div class="d-flex justify-content-end">
			<div class="widget-header">
				<small class="title text-muted">Hello!</small>
				<div> <a href="#">Sign in</a> <span class="dark-transp"> | </span>
				<a href="#"> Register</a></div>
			</div>
			<a href="#" class="widget-header border-left pl-3 ml-3">
				<div class="icontext">
					<div class="icon-wrap icon-sm round border"><i class="fa fa-shopping-cart"></i></div>
				</div>
				<span class="badge badge-pill badge-danger notify">0</span>
			</a>
		</div> <!-- widgets-wrap.// -->
	</div> <!-- col.// -->
</div> <!-- row.// -->
	</div> <!-- container.// -->
</section> <!-- header-main .// -->
</header> <!-- section-header.// -->

<section class="bg2">
<div class="container">
<div class="row no-gutters">
	<div class="col-lg-9 offset-lg-5-24">
<nav class="navbar navbar-expand-lg navbar-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav" aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="main_nav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#"> Home </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">New arrival</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Populars</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Deals</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Last viewed</a>
        </li>

        {{--}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown07" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More</a>
          <div class="dropdown-menu" aria-labelledby="dropdown07">
            <a class="dropdown-item" href="#">Foods and Drink</a>
            <a class="dropdown-item" href="#">Home interior</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Category 1</a>
            <a class="dropdown-item" href="#">Category 2</a>
            <a class="dropdown-item" href="#">Category 3</a>
          </div>
        </li>
        --}}

      </ul>
    </div> <!-- collapse .// -->
</nav>
	</div> <!-- col.// -->
</div> <!-- row.// -->
</div> <!-- container .// -->
</section>


<!-- ========================= SECTION MAIN ========================= -->
<section class="section-main bg1 padding-bottom">
<div class="container">
<div class="row no-gutters border border-top-0 bg-white">
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

    {{--}}
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
    --}}

	</ul>
</nav>
</aside> <!-- col.// -->
<main class="col-lg-19-24">
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
		<a href="#" class="btn btn-primary btn-sm btn-round"> View all </a>
    </li>
    <li class="list-group-item">
		<h6>Group of items goes here</h6>
		<a href="#" class="btn btn-primary btn-sm btn-round"> View all </a>
    </li>
    <li class="list-group-item">
		<h6>Group of items goes here</h6>
		<a href="#" class="btn btn-primary btn-sm btn-round"> View all </a>
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
    <span class="badge-offer"><b> - 50%</b></span>
		<div class="img-wrap"> <img src="img/items/Freshy-Apple-Juice.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">Good item name</a></h6>

			<div class="price-wrap h5">
        <a href="#" class="btn btn-primary btn-sm float-right"> Order </a>
				<span class="price-new">$1280</span>
				<del class="price-old">$1980</del>
			</div> <!-- price-wrap.// -->

		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->
<div class="col-md-3">
	<figure class="card card-product">
		<div class="img-wrap"> <img src="img/items/chabhuoy.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">The name of product</a></h6>
			<div class="price-wrap">
        <a href="#" class="btn btn-primary btn-sm float-right"> Order </a>
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
        <a href="#" class="btn btn-primary btn-sm float-right"> Order </a>
				<span class="price-new">$280</span>
			</div> <!-- price-wrap.// -->
		</figcaption>
	</figure> <!-- card // -->
</div> <!-- col // -->
<div class="col-md-3">
	<figure class="card card-product">
		<div class="img-wrap"> <img src="img/items/chabhuoy.jpg"></div>
		<figcaption class="info-wrap">
			<h6 class="title "><a href="#">The name of product</a></h6>
			<div class="price-wrap">
        <a href="#" class="btn btn-primary btn-sm float-right"> Order </a>
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
        <a href="#" class="btn btn-primary btn-sm float-right"> Order </a>
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
        <a href="#" class="btn btn-primary btn-sm float-right"> Order </a>
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
        <a href="#" class="btn btn-primary btn-sm float-right"> Order </a>
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
        <a href="#" class="btn btn-primary btn-sm float-right"> Order </a>
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








<!-- ========================= FOOTER ========================= -->
<footer class="section-footer bg-secondary">
	<div class="container">
		<section class="footer-top padding-top">
			<div class="row">

        <aside class="col-sm-3  col-md-3 white">
					<h5 class="title">About</h5>
					<ul class="list-unstyled">
						<li> <a href="#"> Our history </a></li>
						<li> <a href="#"> Advertise </a></li>
						<li> <a href="#"> Partnership </a></li>
					</ul>
				</aside>

				<aside class="col-sm-3 col-md-3 white">
					<h5 class="title">Customer Services</h5>
					<ul class="list-unstyled">
						<li> <a href="#">Help center</a></li>
						<li> <a href="#">Terms and Policy</a></li>
            <li> <a href="#"> How to buy </a></li>
						<li> <a href="#"> Delivery and payment </a></li>
					</ul>
				</aside>
				<aside class="col-sm-3  col-md-3 white">
					<h5 class="title">My Account</h5>
					<ul class="list-unstyled">
						<li> <a href="#"> User Login </a></li>
						<li> <a href="#"> User register </a></li>
						<li> <a href="#"> My Orders </a></li>
						<li> <a href="#"> My Wishlist </a></li>
					</ul>
				</aside>

				<aside class="col-sm-3">
					<article class="white">
						<h5 class="title">Contacts</h5>
						<p>
							<strong>Phone: </strong> +123456789 <br>
						    <strong>Fax:</strong> +123456789
						</p>

						 <div class="btn-group white">
						    <a class="btn btn-facebook" title="Facebook" target="_blank" href="#"><i class="fab fa-facebook-f  fa-fw"></i></a>
						    <a class="btn btn-instagram" title="Instagram" target="_blank" href="#"><i class="fab fa-instagram  fa-fw"></i></a>
						    <a class="btn btn-youtube" title="Youtube" target="_blank" href="#"><i class="fab fa-youtube  fa-fw"></i></a>
						    <a class="btn btn-twitter" title="Twitter" target="_blank" href="#"><i class="fab fa-twitter  fa-fw"></i></a>
						</div>
					</article>
				</aside>
			</div> <!-- row.// -->
			<br>
		</section>

		<section class="footer-bottom row border-top-white">
			<div class="col-sm-12">
				<p class="text-white-50"> Copyright &copy {{ Date('Y') }} <a href="#" class="text-white-50">CHABHUOY</a></p>
			</div>
      {{--}}
			<div class="col-sm-6">
				<p class="text-md-right text-white-50">
	Copyright &copy {{ Date('Y') }} <br>
<a href="#" class="text-white-50">CHABHUOY</a>
				</p>
			</div>
      --}}
		</section> <!-- //footer-top -->


	</div><!-- //container -->
</footer>
<!-- ========================= FOOTER END // ========================= -->

</body>
</html>
