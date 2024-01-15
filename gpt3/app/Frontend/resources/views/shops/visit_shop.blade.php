@extends('frontend::layouts.main')

@section('css')
    <style>
        .h-100 {
            height: 250px !important;
            width: 100% !important;
            /* background-color: aqua; */
            /* background-image: url("https://img.lovepik.com/photo/40007/3158.jpg_wh300.jpg"); */
            background-position: center;
            background-repeat: no-repeat, repeat;
            background-size: cover; 
            border-bottom: 1px solid #d4d4d4
        }
        .profile-img{
            height: 150px !important;
            width: 150px  !important;
        }
        .logo-img {
            margin-top: -60px;
        }
        .user-logo {
            height: 70px !important;
            width: 70px !important;
            border-radius: 50% !important;
        }

        @media only screen and (max-width: 573px) {
            .user {
                float: left !important;
            }
        }

        @media only screen and (min-width: 576px) {
            .col-sm-4 {
                flex: 0 0 33.3333333333%;
                max-width: 52.333333%;
            }
        }
       
    </style>
@endsection

@section('content')
    <div class="container bg-white rounded mb-5">
        {{-- <h1>Visit Shop</h1> --}}

        <div class="row">
            <div class="col-sm">
                <div class="h-100 d-inline-block"></div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="d-flex">
                            <div class="logo-img ml-5">
                                <img src="https://thumbs.dreamstime.com/b/profile-placeholder-image-gray-silhouette-no-photo-person-avatar-default-pic-used-web-design-123478397.jpg" class="img-thumbnail profile-img" alt="">
                            </div>
                            <div class="mx-4 mt-4">
                                <h4 class="mb-3">{{ $shop->name ?? '' }}</h4>
                                <span class="py-2"><i class="fa fa-map-marker-alt"></i> {{ $shop->address ?? '' }} | Shop Opened: {{ \Carbon\Carbon::parse($shop->created_at)->format('M Y') }}</span>
                                <p>
                                    <span><i class="far fa-star"></i></span>
                                    <span><i class="far fa-star"></i></span>
                                    <span><i class="far fa-star"></i></span>
                                    <span><i class="far fa-star"></i></span>
                                    <span><i class="far fa-star"></i></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="user mt-3 text-center float-right">
                            <img src="https://thumbs.dreamstime.com/b/profile-placeholder-image-gray-silhouette-no-photo-person-avatar-default-pic-used-web-design-123478397.jpg" class="img-thumbnail user-logo" alt="">
                            <p class="mt-1">Owner : <span>{{ $shop->user->full_name ?? '' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                            Product
                        </a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                            About shop
                        </a>
                        {{-- <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">
                          Location
                        </a> --}}
                    </div>
                  </nav>

                  <div class="my-4">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            @include('frontend::shops.visit_shop_components.products', ['shop' => $shop])
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            @include('frontend::shops.visit_shop_components.about_shop')
                        </div>
                        {{-- <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <h5>Location</h5>
                        </div> --}}
                      </div>
                  </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjD6jIchE8joKfi6Risjv995PoJQPxVxw&callback=initMap&libraries=&v=weekly"
      async
    ></script>
    <script>
        $(document).on('click', '.nav-item', function(e) {
            e.preventDefault();
        });        
    </script>
@endsection