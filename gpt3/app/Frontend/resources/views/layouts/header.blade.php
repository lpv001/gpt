<style>
    .bg-dark-green {
      background-color: #006a4d; color:#fff !important;
    }

    @media screen and (max-width: 991px) {
      .nav-menu {
        display: block !important;
      }
      
    }
</style>

<header class="section-header">
  <nav class="navbar navbar-top navbar-expand-lg navbar-dark bg-orange bg-dark-green">
 
  <div class="container">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTop" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="d-flex">
      <a id="a-notification" href="" class="widget-header mt-2 mb-0 nav-menu mr-3" style="display: none">
        <div class="icontext">
          <div class="icon-wrap icon-sm round border">
            <i class="fa fa-bell text-white" aria-hidden="true"></i>
          </div>
        </div>
        <span class="badge badge-pill badge-danger notify notification">{{ 0 }}</span>
      </a>

      <a href="{{ route('cart') }}" class="widget-header mt-2 mb-0 nav-menu" style="display: none">
        <div class="icontext">
          <div class="icon-wrap icon-sm round border"><i class="fa fa-shopping-cart"></i></div>
        </div>
      
        @if(Auth::user())
          <span class="badge badge-pill badge-danger notify card-data-qty">{{Cart::session(Auth::user()->id)->getContent()->count()}}</span>
        @else
          <span class="badge badge-pill badge-danger notify card-data-qty">{{Cart::getContent()->count()}}</span>
        @endif
      </a>
    </div>
    

    <div class="collapse navbar-collapse" id="navbarTop">
      <ul class="navbar-nav mr-auto">
      <li class="nav-item" >
        <a class="nav-link" href="#"> <i class="fa fa-map-marker-alt"></i> {{__('frontend.deliver_to')}}: {{__('frontend.phnom_penh')}} </a>
      </li>
  
       <li class="nav-item dropdown">
         <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">   {{__('frontend.language')}} </a>
          <ul class="dropdown-menu small">
          <li>
            <a class="dropdown-item {{ session()->get('locale') ? (session()->get('locale') === 'en' ? 'active' : '' ): '' }}" href="{{ url('/lang/en') }}">
              <img src="{{ asset('/img/icons/uk.png') }}" alt="" widht="30" height="20"> English 
            </a>
            </li>
                <li>
            <a class="dropdown-item {{ session()->get('locale') ? (session()->get('locale') === 'km' ? 'active' : '' ): '' }}" href="{{ url('/lang/km') }}">
              <img src="{{ asset('/img/icons/km.png') }}" alt="" widht="20" height="13">  ខ្មែរ
            </a>
            </li>
          </ul>
      </li>
      </ul>
      <ul class="navbar-nav">

      <li><a href="{{route('my.account')}}" class="nav-link"> {{__('frontend.my_account')}} </a></li>
      @guest
          <li><a href="" class="nav-link"> {{__('frontend.became_a_seller')}} </a></li>
          <li class="nav-menu" style="display: none;"><a href="{{route('my.account')}}" class="nav-link"> {{__('frontend.sign_in_account')}} </a></li>
      @else
        @if (auth()->user()->shop_id === null || auth()->user()->shop_id === 0)
          <li><a href="" class="nav-link">{{__('frontend.became_a_seller')}}</a></li>
        @else
          <li><a href="" class="nav-link"> {{__('frontend.my_shop')}} </a></li>
        @endif

        <li class="nav-menu" style="display: none">
          <a href="{{ route('logout') }}" style="color: rgba(255, 255, 255, 0.5)"
          onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
          {{ __('frontend.sign_out') }}
      </a>
        </li>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
      @endguest
  
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
          <img class="logo" src="{{ asset('img/logo.png')}}">
          <h2 class="logo-text">{{ env('APP_NAME') }}</h2>
        </a>
      </div> <!-- brand-wrap.// -->
    </div>
    <div class="col-lg-11-24 col-sm-12 order-3 order-lg-2">
      <form action="{{ route('search') }}" method="get">
        <div class="input-group w-100">
            <input type="text" id="q" name="q" class="form-control" style="width:40%;" placeholder="Search" value="{{ old('q') }}">
            <div class="input-group-append">
              <button class="btn btn-warning" type="submit">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
      </form> <!-- search-wrap .end// -->
    </div> <!-- col.// -->
    <div class="col-lg-8-24 col-sm-7 col-8  order-2  order-lg-3">
      <div class="d-flex justify-content-end">
        <div class="widget-header">
  
          @guest
            <small class="title text-muted">{{__('frontend.hello')}}!</small>
          @else
            <small class="title text-muted">{{__('frontend.hello')}} {{ Auth::user()->full_name }}!</small>
          @endguest
  
          <div>
              @guest
                <a href="{{ url('login')}}">{{__('frontend.sign_in_account')}}</a> <span class="dark-transp"> | </span>
                <a href="{{ url('register')}}"> {{__('frontend.sign_up')}}</a>
  
              @else
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    {{ __('frontend.sign_out') }}
                </a>
  
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              @endguest
  
          </div>
    </div>
  
        <a id="a-notification" href="" class="widget-header pl-3 ml-3">
          <div class="icontext">
            <div class="icon-wrap icon-sm round border">
              <i class="fa fa-bell" aria-hidden="true"></i>
            </div>
          </div>
          <span class="badge badge-pill badge-danger notify notification">{{ 0 }}</span>
        </a>
  
      <a href="{{ route('cart') }}" class="widget-header pl-3 ml-3">
        <div class="icontext">
          <div class="icon-wrap icon-sm round border"><i class="fa fa-shopping-cart"></i></div>
        </div>
      
        @if(Auth::user())
          <span class="badge badge-pill badge-danger notify card-data-qty">{{Cart::session(Auth::user()->id)->getContent()->count()}}</span>
        @else
        <span class="badge badge-pill badge-danger notify card-data-qty">{{Cart::getContent()->count()}}</span>
        @endif
      </a>
    </div> <!-- widgets-wrap.// -->
    </div> <!-- col.// -->
  </div> <!-- row.// -->
    </div> <!-- container.// -->
  </section> <!-- header-main .// -->
  
  </header> <!-- section-header.// -->
  