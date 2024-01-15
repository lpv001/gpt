<style>
    .data-lists :hover {
      cursor: pointer;
      border-left: 0px solid sandybrown;
      background-color: rgb(240, 240, 240);
    }
    .data-lists-active {
      cursor: pointer;
      border-left: 3px solid sandybrown;
      background-color: rgb(240, 240, 240);
    }
</style>


<div class="card p-3">
  <div class="pt-3 p-2 border-bottom">
    <h5 class="title font-weight-bold">
      <span class="mr-2"><i class="fa fa-cog" aria-hidden="true"></i></span> 
      {{ __('frontend.account') }}</h5>
  </div>

  <div class="d-flex flex-column data-lists {{ \Request::is('my/account') ? 'data-lists-active' : ''}}" >
    <a href="{{ route('my.account') }}">
      <div class="p-2">{{ __('frontend.my_profile') }}</div>
    </a>
  </div>

  {{--
  <div class="d-flex flex-column data-lists {{ \Request::is('delvery-address') ? 'data-lists-active' : ''}}">
    <div class="p-2">
      Delivery Address</div>
  </div>
  --}}

  <div class="d-flex flex-column data-lists {{ \Request::is('orders') ? 'data-lists-active' : ''}}">
    <a href="{{ route('orders.index') }}">
      <div class="p-2">{{ __('frontend.my_orders') }}</div>
    </a>
  </div>
</div>

<div class="card p-3 mt-4">
  <div class="pt-3 p-2 border-bottom">
    <h5 class="title font-weight-bold">
      <span class="mr-2"><i class="fa fa-cog" aria-hidden="true"></i></span> 
      {{ __('frontend.shop') }}</h5>
  </div>

  <!--
  <div class="d-flex flex-column data-lists {{ request()->is('my-account/shop') ? 'data-lists-active' : '' }}">
    <a href="{{ route('shop.index') }}">
      <div class="p-2">{{ __('frontend.my_shop') }}</div>
    </a>
  </div>
  -->
  
  @if (auth()->user()->shop_id > 0)
  <div class="d-flex flex-column data-lists data-lists-active">
    <a href="{{ route('shop.index') }}">
      <div class="p-2">{{ __('frontend.my_shop') }}</div>
    </a>
  </div>
  @else
  <div class="d-flex flex-column data-lists data-lists-active">
    <a href="{{ route('shop.index') }}">
      <div class="p-2">{{ __('frontend.become_seller') }}</div>
    </a>
  </div>
  @endif

  {{--
  <div class="d-flex flex-column data-lists">
    <div class="p-2">
      Delivery Address</div>
  </div>

  <div class="d-flex flex-column data-lists">
    <div class="p-2">
      Purchase Orders</div>
  </div>
  --}}
  
</div>



