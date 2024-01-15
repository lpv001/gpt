<div class="card-header">
	<div class="mx-auto" style="width:65%">
		<div class="d-flex justify-content-end">
			<div class="">
				<a class="nav-link text-white" href="#"> <i class="fa fa-map-marker-alt"></i> {{__('frontend.deliver_to')}}: {{__('frontend.phnom_penh')}} </a>
			</div>
			<div class="mx-2">
				<ul class="navbar-nav mr-auto">
					 <li class="nav-item dropdown">
						 <a href="#" class="nav-link dropdown-toggle text-white" data-toggle="dropdown">
    						 @if(session()->get('locale') === 'en')
    							 <span>
    								 <img src="{{ asset('/img/icons/uk.png') }}" alt="" widht="30" height="20">
    							</span> 
    							<span class="mx-2 text-white">|</span> English
    						@endif
    						 @if(session()->get('locale') === 'km')
    							 <span>
    								 <img src="{{ asset('/img/icons/km.png') }}" alt="" widht="30" height="20">
    							</span> 
    							<span class="mx-2 text-white">|</span>{{ __('frontend.khmer') }}
    						@endif

						</a>
						<ul class="dropdown-menu small">
							<li>
								<a class="dropdown-item  {{ session()->get('locale') ? (session()->get('locale') === 'en' ? 'active' : '' ): '' }}" href="{{ url('/lang/en') }}">
									<img src="{{ asset('/img/icons/uk.png') }}" alt="" widht="30" height="20"> English 
								</a>
							</li>
							<li>
								<a class="dropdown-item {{ session()->get('locale') ? (session()->get('locale') === 'km' ? 'active' : '' ): '' }}" href="{{ url('/lang/km') }}">
									<img src="{{ asset('/img/icons/km.png') }}" alt="" widht="20" height="13"> {{ __('frontend.khmer') }}
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			
			<div class="mx-2">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle text-white" data-toggle="dropdown"> 
							<span class="mx-2">
								<i class="fa fa-user" aria-hidden="true"></i>
						   </span> 
							 {{ auth()->user() ? auth()->user()->full_name : __('frontend.account') }}
					   </a>
					   <ul class="dropdown-menu small">
						   @guest
								<li>
									<a class="dropdown-item" href="{{ route('login') }}">
										{{-- <i class="fa fa-sign-in" aria-hidden="true"></i> --}}
										{{ __('frontend.login_account') }}
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="{{ url('register') }}">
										{{ __('frontend.sign_up') }}
									</a>
								</li> 
						   @else
								<li>
									<a class="dropdown-item" href="{{ route('my.account') }}">
										{{-- <span class="mr-2"><i class="fa fa-user text-sm text-warning" aria-hidden="true"></i></span> --}}
										
										{{ __('frontend.account_settings') }}
									</a>
								</li>
								{{-- <li>
									<a class="dropdown-item" href="{{ url('register') }}">
										Beocone a seller
									</a>
								</li>  --}}
								<li>
									{{-- <a class="dropdown-item" href="{{ url('register') }}">
										Sign Out
									</a> --}}

									<a class="dropdown-item" href="{{ route('logout') }}"
										onclick="event.preventDefault();
													document.getElementById('logout-form').submit();">
										{{ __('frontend.sign_out') }}
									</a>

									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										@csrf
									</form>
								</li> 
						   @endguest
						   	
						</ul>
				   </li>
			   </ul>
			</div>
		</div>
	</div>
</div>

{{-- Mobile Logo --}}
<div class="bg-white px-3 pt-3 py-2 mobile-header">
	<div class="d-flex justify-content-between align-items-center">
		<div class="logo d-flex align-items-center">
			<img class="mobile-logo" src="{{ asset('img/logo.png') }}" alt="">
			<div class="ml-2">
				<a href="/" class="h5 font-weight-bold text-warning">
					{{ env('APP_NAME')}}
				</a>
			</div>
			
		</div>
		<div>
			<a href="{{ route('cart') }}" class="widget-header">
				<div class="icontext">
					<div class="icon-wrap icon-sm round border">
						<img class="icon-shoping-cart" src="{{ asset('img/icons/shopping-cart.svg')}}" alt="">
						{{-- <i class="fas fa-shopping-cart"></i> --}}
					</div>
				</div>
			
				<span class="badge badge-pill badge-danger notify shopping-cart-qty">{{Cart::getContent()->count()}}</span>
			</a>
		</div>
	</div>

	<div class="d-flex">
		<form action="{{ route('search') }}" method="get" class="w-100">
			<div class="input-group">
				<input type="text" name="q" class="form-control" style="width:40%;" placeholder="{{ __('frontend.search') }}">
				<div class="input-group-append">
				  <button class="btn btn-warning" type="submit">
					<i class="fa fa-search"></i>
				  </button>
				</div>
			</div>
		</form> <!-- search-wrap .end// -->
	</div>
</div>
{{--  --}}

{{-- Mobile Search --}}
<div class="card m-3 mobile-header mobile-header-search">
	<div class="d-flex">
		<form action="{{ route('search') }}" method="get" class="w-100">
			<div class="input-group">
				<input type="text" name="q" class="form-control" style="width:40%;" placeholder="{{ __('frontend.search') }}">
				<div class="input-group-append">
				  <button class="btn btn-warning" type="submit">
					<i class="fa fa-search"></i>
				  </button>
				</div>
			</div>
		</form> <!-- search-wrap .end// -->
	</div>
</div>

{{--  --}}
<div style="background-color: #fff" class="header">
	<div class="container py-3">
		<div class="row">
			<div class="col-3 d-flex align-items-center">
				<img class="logo" src="{{ asset('img/logo.png') }}" width="20%">
				<a href="/" class="mx-2 mt-2 h4">
					{{ env('APP_NAME')}}
				</a>

				<div class="header-list-cat  display-cat-list d-flex justify-content-around align-items-center py-2 border rounded px-2 pt-2">
					<dl class="mb-0 header-menu-cat">
						<dt class="d-flex">
								<i class="fa fa-bars h4 mb-0" aria-hidden="true"></i>
								<i class="ml-2 fa fa-caret-down" aria-hidden="true"></i>
						</dt>
						<dd class="header-cat">
						@if (isset($categories))
							<div class="category-list-group" >
								@foreach (@$categories as $item)
									<dl class="cl-item mb-0 py-1">
										<dt class="px-1">
											<span>
												<img class="mr-2" style="width: 20%" src="{{ $item['image_name'] }}" alt="">
											</span>
											<a href="{{ route('search.category', $item['id']) }}" class="font-weight-normal">{{$item['default_name']}}</a>
										</dt>
										@if (count($item['sub_categories']) > 0)
										<dd class="sub-cat">
											<div class="sub-cat-main">
												<div class="sub-cate-content">
													<div class="row py-3">
														<div class="col-9">
															<div class="row">
																
																@foreach ($item['sub_categories'] as $sub_cat)
																	<div class="col-4 mb-3">
																		<dl class="mb-0">
																			<dt style="font-weight: 900">
																				<img class="mr-2" style="width: 35%" src="{{ $sub_cat['image_name'] }}" alt="">
																					<a class="sub-cate-title" href="{{ route('search.category', $sub_cat['id']) }}">
																							{{$sub_cat['default_name']}}
																					</a>
																			</dt>
																			<dd class="pl-3">
																				<div class="d-flex flex-column" style="word-wrap: break-word;">
																					@if (count($sub_cat['sub_categories']) > 0)
																						@foreach ($sub_cat['sub_categories'] as $sub_cat2)
																							<small class="">
																								<a class="sub-cate-title" href="{{ route('search.category', $sub_cat2['id']) }}">{{$sub_cat2['default_name']}}</a>
																							</small>
																						@endforeach
																					@endif
																				</div>
																			</dd>
																		</dl>
																	</div>
																@endforeach
															
															</div>
														</div>  
													</div>
												</div>
											</div>
										</dd>
										@endif
									</dl>
								@endforeach
							</div>
						@endif
						</dd>
					</dl>
				</div>
			</div>
			<div class="col-7 d-flex align-items-center">
				<form action="{{ route('search') }}" method="get" class="w-100">
					<div class="input-group">
						{{-- {{ dd() }} --}}
						<input type="text" name="q" value="{{ Request::get('q') }}" class="form-control" style="width:40%;" placeholder="{{ __('frontend.search') }}">
						<input type="hidden" name="min_price" value="{{ Request::get('min_price') }}">
						<input type="hidden" name="max_price" value="{{ Request::get('max_price') }}">
						<div class="input-group-append">
						  <button class="btn btn-warning" type="submit">
							<i class="fa fa-search"></i>
						  </button>
						</div>
					</div>
				</form> <!-- search-wrap .end// -->
			</div>
			<div class="col-2 d-flex align-items-center justify-content-start">
				<a href="{{ route('cart') }}" class="widget-header pl-3 ml-3">
					<div class="icontext">
						<div class="icon-wrap icon-sm round border"><i class="fa fa-shopping-cart"></i></div>
					</div>
				
					<span class="badge badge-pill badge-danger notify shopping-cart-qty">{{Cart::getContent()->count()}}</span>
	
				</a>
			</div>
		</div>
	</div>
</div>

