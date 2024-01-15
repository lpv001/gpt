@php
    if (session()->has('locale')) {
      \App::setLocale(session()->get('locale'));
    } else {
      \App::setLocale('en');
    }
@endphp

<footer class="section-footer">
	<div class="container">
		<section class="footer-top padding-top border-top">
			<div class="row">

			<aside class="col-sm-3  col-md-3">
				<h5 class="title">{{ __('frontend.about') }}</h5>
						<div>
							<strong>{{ __('frontend.business_title', ['app_name' => env('APP_NAME')]) }}</strong><br>
							#14S(7S), Street 200R, Km6 Russey Keo, Phnom Penh, Cambodia<br>
						    <strong>Tel:</strong> +855 15 337 555
						</div>
		    </aside>

				<aside class="col-sm-3 col-md-3">
					<h5 class="title">{{ __('frontend.customer_services') }}</h5>
					<ul class="list-unstyled">
						<li> <a href="#">{{ __('frontend.support_center') }}</a></li>
						<li> <a href="{{ env('PUB_URL') }}/privacy">{{ __('frontend.privacy_policy') }}</a></li>
						<li> <a href="{{ env('PUB_URL') }}/terms">{{ __('frontend.terms') }}</a></li>
						<li> <a href="#">{{ __('frontend.how_to_buy') }}</a></li>
						<li> <a href="#">{{ __('frontend.delivery_and_payment') }}</a></li>
					</ul>
				</aside>
				<aside class="col-sm-3  col-md-3">
					<h5 class="title">{{ __('frontend.my_account') }}</h5>
					<ul class="list-unstyled">
						<li> <a href="{{ env('PUB_URL') }}/login">{{ __('frontend.user_signin') }}</a></li>
						<li> <a href="{{ env('PUB_URL') }}/register">{{ __('frontend.user_register') }}</a></li>
						<li> <a href="{{ route('orders.index') }}">{{ __('frontend.my_orders') }}</a></li>
					</ul>
				</aside>

				<aside class="col-sm-3">
					<article>
						<h5 class="title">{{ __('frontend.follow_us') }}</h5>
						 <div class="btn-group">
						    <a class="btn btn-facebook" title="Facebook" target="_blank" href="https://www.facebook.com/store.ganzberg"><i class="fab fa-facebook-f  fa-fw"></i></a>
						    <a class="btn btn-instagram" title="Instagram" target="_blank" href="https://www.instagram.com/ganzberg_beer"><i class="fab fa-instagram  fa-fw"></i></a>
						    <a class="btn btn-youtube" title="Youtube" target="_blank" href="https://www.youtube.com/channel/UCLIqEWU0EgpJlFwLfuGGmnA?view_as=subscriber"><i class="fab fa-youtube  fa-fw"></i></a>
						    <a class="btn btn-twitter" title="Twitter" target="_blank" href="#"><i class="fab fa-twitter  fa-fw"></i></a>
						</div>
					</article>
				</aside>
			</div> <!-- row.// -->
			<br>
		</section>

		<section class="footer-bottom row border-top">
			<div class="col-sm-12">
				<p class="text-black-100"> Copyright &copy {{ Date('Y') }} <a href="#" class="text-black-70">{{ __('frontend.business_title', ['app_name' => env('APP_NAME')]) }}</a></p>
			</div>
		</section> <!-- //footer-top -->


	</div><!-- //container -->
	<div class="bottom-navigation-bar border-top">
		<div class="row">
			<div class="col-3  text-center">
				<a href="/" class="title-bottom d-flex flex-column {{ request()->is('/') ? 'text-warning' : '' }}">
					<i class="fa fa-home h5 mb-0" aria-hidden="true"></i>
					<span class="">Home</span>
				</a>
			</div>
			<div class="col-3 d-flex flex-column text-center">
				<a href="/search" class="title-bottom d-flex flex-column {{ request()->is('category') ? 'text-warning' : '' }}">
					<i class="fa fa-list h5 mb-0" aria-hidden="true"></i>
					<span>Products</span>
				</a>
			</div>
			<div class="col-3 d-flex flex-column text-center">
				<a href="/cart" class="title-bottom d-flex flex-column {{ request()->is('cart') ? 'text-warning' : '' }}">
					<img class="icon-shoping-cart mx-auto"  src="{{ asset('img/icons/shopping-cart.svg')}}" alt="">
					<span>Cart</span>
				</a>
			</div>
			<div class="col-3 d-flex flex-column text-center">
				<a href="/my/account" class="title-bottom d-flex flex-column {{ request()->is('my/account') ? 'text-warning' : '' }}">
					<i class="fa fa-user-circle h5 mb-0" aria-hidden="true"></i>
					<span>Account</span>
				</a>
			</div>
		</div>
	</div>
</footer>
