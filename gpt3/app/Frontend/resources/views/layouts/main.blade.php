<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="Bootstrap-ecommerce by Vosidiy">
<meta name="csrf-token" content="{{ csrf_token() }}">



@php
    if (empty($settings))
        $settings = ['title' => ''];
    else
        $settings['title'] = $settings['title'] . ' - ';
@endphp

<title>{{ $settings['title'] . __('frontend.business_title', ['app_name' => env('APP_NAME')]) }}</title>
{{--}}
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('ui-ecommerce/images/favicon.ico')}}">
--}}
<link href="{{ asset('css/app.css') }}" rel="stylesheet">


<!-- jQuery -->
<script src="{{ asset('ui-ecommerce/js/jquery-2.0.0.min.js')}}" type="text/javascript"></script>

<!-- Bootstrap4 files-->
<script src="{{ asset('ui-ecommerce/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
{{--}}
<link href="{{ asset('ui-ecommerce/css/bootstrap.css')}}" rel="stylesheet" type="text/css"/>
--}}
<!-- Font awesome 5 -->
<link href="{{ asset('ui-ecommerce/fonts/fontawesome/css/fontawesome-all.min.css')}}" type="text/css" rel="stylesheet">

{{-- Font --}}
<link rel="preconnect" href="https://fonts.gstatic.com">
{{-- <link href="https://fonts.googleapis.com/css2?family=Angkor&family=Battambang&family=Content&family=Hanuman&display=swap" rel="stylesheet"> --}}
<link href="https://fonts.googleapis.com/css2?family=Battambang&family=Content&display=swap" rel="stylesheet">

<!-- plugin: fancybox  -->
<script src="{{ asset('ui-ecommerce/plugins/fancybox/fancybox.min.js')}}" type="text/javascript"></script>
<link href="{{ asset('ui-ecommerce/plugins/fancybox/fancybox.min.css')}}" type="text/css" rel="stylesheet">

<!-- plugin: owl carousel  -->
<link href="{{ asset('ui-ecommerce/plugins/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
<link href="{{ asset('ui-ecommerce/plugins/owlcarousel/assets/owl.theme.default.css')}}" rel="stylesheet">
<script src="{{ asset('ui-ecommerce/plugins/owlcarousel/owl.carousel.min.js')}}"></script>

<!-- custom style -->
<link href="{{ asset('ui-ecommerce/css/ui.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('ui-ecommerce/css/responsive.css')}}" rel="stylesheet" media="only screen and (max-width: 1200px)" />
<!--<link href="{{ asset('ui-ecommerce/css/style.css')}}" rel="stylesheet" type="text/css"/>-->

{{-- Plugin Image --}}
<link href="{{ asset('/css/image-uploader.min.css') }}" rel="stylesheet" />
<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('/css/scrollpagination.css') }}" rel="stylesheet" />


<!-- Custom Styles -->
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">


<link href="{{ asset('css/home-page.css') }}" rel="stylesheet">
<link href="{{ asset('css/search-page.css') }}" rel="stylesheet">

{{-- snackbar --}}
<link href="{{ asset('css/snackbar.css') }}" rel="stylesheet">

<link href="{{ asset('css/header.css') }}" rel="stylesheet">
<link href="{{ asset('css/main-body.css') }}" rel="stylesheet">
<link href="{{ asset('css/footer.css') }}" rel="stylesheet">

{{-- sweet alert style --}}
<link rel="stylesheet" href="{{ asset('css/sweetalert_custom.css')}}">

<!-- custom javascript -->
<script src="{{ asset('ui-ecommerce/js/script.js')}}" type="text/javascript"></script>

<!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-messaging.js"></script>	
<script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-database.js"></script>	

{{-- sweet alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<style>
	html,body {
		height: 100%;
		/* font-family: Arial, Verdana, sans-serif; */
		font-family: "OpenSans","Open Sans",Arial,Helvetica,sans-serif,"SimSun", 'Battambang', cursive;
	}
	.text-shop {
		font-size: 17px;
		font-weight:100;
	}
	.shop-info:hover{
		cursor: pointer;
		text-decoration: underline;
	}
	.small-text{
		font-size: 14px;
	}
</style>

@yield('styles')

</head>
<body>
<!-- ========================= SECTION MAIN ========================= -->
@include('frontend::layouts.header_new')
<!-- ========================= SECTION MAIN END // ========================= -->


<!-- ========================= SECTION MAIN ========================= -->
@yield('content')
<!-- ========================= SECTION CONTENT END// ========================= -->


<!-- ========================= FOOTER ========================= -->
@include('frontend::layouts.footer')
<!-- ========================= FOOTER END // ========================= -->


<!-- ========================= Script // ========================= -->
<script src="{{  asset('/js/image-uploader.js') }}"></script>
<script src="{{  asset('/js/select2.min.js') }}"></script>
<script src="{{  asset('/js/jquery.zoom.js') }}"></script>
<script src="{{  asset('/js/scrollpagination.js') }}"></script>
<script src="{{  asset('/js/firebase/firebase.js') }}"></script>

@yield('scripts')
	
	<script>
		window.onscroll = function() {myFunction()};
		// select2 select input
		$('.select2').select2();

		var header = document.querySelector(".header");
		var sticky = header.offsetTop;

		function myFunction() {
			if (window.pageYOffset > sticky) {
				header.classList.add("sticky");
			} else {
				header.classList.remove("sticky");
			}
		}

		$(".number-only").keypress(function (e) {
			//if the letter is not digit then display error and don't type anything
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});

		$(".number").on("keypress keyup blur",function (event) {
			$(this).val($(this).val().replace(/[^0-9\.]/g,''));
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
				event.preventDefault();
			}
		});

		function sendTokenToServer(fcm_token) {
			$.ajax({
				type: "POST",
				url: "{{ route('frontend.store-fcm-token') }}",
				data: {
					fcm_token,
					_token: "{{ csrf_token()}}",
				},
				success: function (response) {},
			});
		}

		$('.header-list-cat').removeClass('display-cat-list');

		$(window).scroll(function(){
			var sticky = $('.sticky'),
				scroll = $(window).scrollTop();
				
			//if (scroll >= 100) {	
				//$('.header-logo').hide();
				//$('.header-list-cat').removeClass('display-cat-list');
				//sticky.addClass('fixed')
			//} else {
        //alert("holllll");
				//$('.header-logo').show();
				//$('.header-list-cat').addClass('display-cat-list');
				//sticky.removeClass('fixed');
			//}

		});

	</script>
</body>
</html>
