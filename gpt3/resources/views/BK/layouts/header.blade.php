<header class="section-header">
<nav class="navbar navbar-top navbar-expand-lg navbar-dark bg-orange">
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
      <a href="/">
  			{{-- <img class="logo" src="ui-ecommerce/images/logo-dark.png"> --}}
        <img class="logo" src="{{ asset('img/gg_logo.png')}}">
  			<h2 class="logo-text">{{ env('APP_NAME', 'GanGos')}}</h2>
      </a>
    </div> <!-- brand-wrap.// -->
	</div>
	<div class="col-lg-13-24 col-sm-12 order-3 order-lg-2">
		<form action="#">
			<div class="input-group w-100">
        {{--}}
				<select class="custom-select"  name="category_name">
						<option value="">All type</option><option value="codex">Special</option>
						<option value="comments">Only best</option>
						<option value="content">Latest</option>
				</select>
        --}}
			    <input type="text" class="form-control" style="width:60%;" placeholder="Search">

			    <div class="input-group-append">
			      <button class="btn btn-warning" type="submit">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		    </div>
		</form> <!-- search-wrap .end// -->
	</div> <!-- col.// -->
	<div class="col-lg-6-24 col-sm-7 col-8  order-2  order-lg-3">
		<div class="d-flex justify-content-end">
			<div class="widget-header">

        @guest
          <small class="title text-muted">Hello!</small>
        @else
          <small class="title text-muted">Hello {{ Auth::user()->name }}!</small>
        @endguest

				<div>
            @guest
              <a href="{{ url('login')}}">Sign in</a> <span class="dark-transp"> | </span>
				      <a href="{{ url('register')}}"> Register</a>

            @else
              <a href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            @endguest

        </div>


			</div>
			<a href="{{ route('cart') }}" class="widget-header border-left pl-3 ml-3">
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


<nav class="navbar navbar-expand-lg navbar-dark1 bg-waring border-bottom">
  <div class="container">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav" aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="main_nav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link pl-0" href="#"> <strong>All category</strong></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Beer</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Soft Drinks</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Drinking Water</a>
        </li>

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
      </ul>
    </div> <!-- collapse .// -->
  </div> <!-- container .// -->
</nav>



</header> <!-- section-header.// -->


{{--
<section class="bg2">
<div class="container">
<div class="row no-gutters">

  <div class="col-lg-5-24 col-sm-3">
	<div class="category-wrap dropdown py-1 show">
		<button type="button" style="padding: 0.7rem 0.75rem;" class="btn btn-block btn-light  dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-bars"></i> Categories</button>
		<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 39px, 0px); top: 0px; left: 0px; will-change: transform;">
			<a class="dropdown-item" href="#">Machinery / Mechanical Parts / Tools </a>
			<a class="dropdown-item" href="#">Consumer Electronics / Home Appliances </a>
			<a class="dropdown-item" href="#">Auto / Transportation</a>
			<a class="dropdown-item" href="#">Apparel / Textiles / Timepieces </a>
			<a class="dropdown-item" href="#">Home &amp; Garden / Construction / Lights </a>
			<a class="dropdown-item" href="#">Beauty &amp; Personal Care / Health </a>
		</div>
	</div>
	</div>



	<div class="col-lg-19-24 1offset-lg-5-24">
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


      </ul>
    </div> <!-- collapse .// -->
</nav>
	</div> <!-- col.// -->




</div> <!-- row.// -->
</div> <!-- container .// -->
</section>

--}}
