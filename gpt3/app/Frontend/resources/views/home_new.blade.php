
@extends('frontend::layouts.main')
@section('styles')
    {{-- <style>
        
    </style> --}}
@endsection
@section('content')

<style>
	.fa-shopping-cart{
		margin-top: 0.3em !important;
	}

	.banner-silder {
		height: 400px !important
	}

	@media only screen and (max-width: 991px) {
		.banner-silder {
			height: 11em !important;
		}
	}

	@media only screen and (max-width: 768px) {
		.banner-silder {
			height: 11em !important;
		}
	}

	@media only screen and (max-width: 506px) {
		.banner-silder {
			height: 240px !important;
		}
	}
</style>

<div class="main-container">
    <div class="home-first-main">
        {{-- Category Main --}}
        <div class="home-r1-c1 category-list-items pr-0" style="height:inherit !important">
        @if(isset($categories))
            @include('frontend::components.categories_menu', ['categories' => $categories])
        @endif
        </div>

        {{-- Advertise Main --}}
        <div class="home-r1-c2 ml-3 pr-0">
           <div class="d-flex justify-content-between" style="height: 100%">
               <div class="channel-banner-main">
                    {{-- Banner --}}
                    <div style="height: 70%">
                        @include('frontend::components.home_banner', ['banners' => $banners])
                    </div>
                    
                    {{-- Advertising --}}
                    <div class="advertise-card card mt-2" style="height: 30%">
                    @if (@$brands)    
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="font-weight-bold text-uppercase my-2">
                                {{ __('frontend.top_brands') }}
                            </h5>
                            <a href="{{route('search.brand', 0)}}" class="see-more">{{ __('frontend.see_more') }}</a>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            @include('frontend::components.home_advertise', ['adv_products' => $random_products])
                        </div>
                    @endif
                        
                        
                    </div>
               </div>
           </div>
        </div>

        {{-- Account Main --}}
        <div class="home-r1-c3 ml-3">
            <div class="card " style="width:100%; height:100%">
                <!--
                <h6 class="card-title px-3 pt-3 pb-0">
                    <span class="mr-2">
                    <i class="fa fa-box" aria-hidden="true"></i>
                    </span> 
                    brands
                </h6>
                -->
                {{-- Avatar --}}
                @include('frontend::components.avatar')
                {{-- User menu --}}
                @auth
                    <div class="d-flex justify-content-around text-center">
                        @include('frontend::components.my_menu')
                    </div>
                @endauth

                <div class="card m-2 p-3" 
                    style="
                    height: 50%; width:90%;
                    background-image: url('https://ae01.alicdn.com/kf/H4d81c0b772ad404b9acc330f16c6165dO.png_.webp');
                    background-repeat:no-repeat;
                    background-size: 100% 100%;
                    ">
                    <div class="h4 text-center text-white font-weight-bold">Coming soon !</div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($categories))
    <div class="row card mobile-main p-3 mb-category-list">
        <div class="d-flex flex-wrap mb-category-items">
            <a href="#" class="mb-category text-center">
                <img class="category-img" src="https://png.pngtree.com/png-vector/20190116/ourlarge/pngtree-vector-list-icon-png-image_322087.jpg" alt="">
                <p>All Categories</p>
            </a>
            @foreach ($categories as $key => $category)
                @php
                    if($key > 8) break;
                @endphp

                <a href="{{ route('search.category', $category['id']) }}" class="mb-category text-center">
                    <img class="category-img" src="{{ $category['image_name'] }}" alt="">
                    <p>{{$category['default_name']}}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Top Rankings Web --}}
    <div class="row card mobile-main">
        <div class="col-12 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold h4 text-uppercase font-weight-bold my-2">{{ __('frontend.top_rankings') }}</h5>
                <a href="#" class="see-more">{{ __('frontend.see_more') }}</a>
            </div>

            <div class="d-flex justify-content-between">
                    @foreach ($random_products as $key => $product)
                    @php
                        if($key > 5) break;
                    @endphp
                    <div class="p-ranks">
                        @include('frontend::components.product_medium', ['product' => $product])
                    </div>
                @endforeach
                
            </div>
        </div>
    </div>
    {{-- End Top Rankings --}}

    {{-- Featured Categories --}}
    {{-- <div class="row my-4 ml-0 mobile-products mr-1">
        <div class="col-12 px-4 py-3 d-flex justify-content-center align-items-center">
            <div class="d-line"></div>
            <div class="h4 font-weight-bold">Feature</div>
            <div class="d-line"></div>
        </div>
    </div> --}}
    {{-- End Featured Categories --}}

    <div class="row mobile-main feature-news">
        <div class="col-6 p-0 pr-2">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold h5 text-uppercase font-weight-bold my-2">{{ __('frontend.top_selections') }}</h5>
                    <a href="#" class="see-more">{{ __('frontend.see_more') }}</a>
                </div>
                
                <div class="row mb-4">
                    @foreach ($new_products as $key => $product)
                        @php
                            if ($key > 2) break;
                        @endphp
                        @include('frontend::components.product_small', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-6 p-0 pl-2">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold h5 text-uppercase font-weight-bold my-2">{{ __('frontend.new_arrivals') }}</h5>
                    <a href="#" class="see-more">{{ __('frontend.see_more') }}</a>
                </div>

                <div class="row mb-4">
                    @foreach ($random_products as $key => $product)
                        @php
                            if ($key > 2) break;
                        @endphp
                        @include('frontend::components.product_small', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </div>
    </div>

     {{-- Featured Categories --}}
    <div class="row mobile-main" id="p-pagination">
        <div class="col-12 px-4 py-3 d-flex justify-content-center align-items-center">
            <div class="d-line"></div>
            <div class="h4 font-weight-bold">More Products</div>
            <div class="d-line"></div>
        </div>

        <div class="d-flex justify-content-between flex-wrap" id="pLists">
            @foreach ($new_products as $key => $product)
                @include('frontend::components.product_medium', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-2 mx-auto spinner">
            <img src="{{ asset('img/circle-spinner.gif') }}" alt="">
        </div>
    </div>
    {{-- End Featured Categories --}}


    {{-- <div class="row card my-4 ml-0 mobile-products">
        <div class="col-12 px-4 py-3">
            <h5 class="font-weight-bold text-uppercase my-3">{{ __('frontend.popular_product') }}</h5>

            <div class="d-flex flex-wrap">
                <div class="owl-carousel owl-theme w-100">
                    @include('frontend::components.product', ['products' => $random_products])
                </div>
            </div>
        </div>
    </div> --}}

</div>
@endsection

@section('scripts')
<script src="{{  asset('/js/product-pagination.js') }}"></script>
@endsection
