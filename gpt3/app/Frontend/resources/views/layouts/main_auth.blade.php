<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="Bootstrap-ecommerce by Vosidiy">

<title>Ganzberg Online Shops</title>
{{--}}
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('ui-ecommerce/images/favicon.ico')}}">
--}}

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

<!-- jQuery -->
<script src="{{ asset('ui-ecommerce/js/jquery-2.0.0.min.js')}}" type="text/javascript"></script>

<!-- Bootstrap4 files-->
<script src="{{ asset('ui-ecommerce/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
{{--}}
<link href="{{ asset('ui-ecommerce/css/bootstrap.css')}}" rel="stylesheet" type="text/css"/>
--}}
<!-- Font awesome 5 -->
<link href="{{ asset('ui-ecommerce/fonts/fontawesome/css/fontawesome-all.min.css')}}" type="text/css" rel="stylesheet">

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

{{-- sweet alert style --}}
<link rel="stylesheet" href="{{ asset('css/sweetalert_custom.css')}}">

{{-- sweet alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<style>
	* {
		font-family: 'Roboto', sans-serif;
 	}
    .btn-warning {
		width:100%;
        background-color: #69b928 !important;
		border: 1px solid #E8E8E8;
    	color: #fff !important;
	}
	.dropdown-item:active {
		background-color: #69b928 !important;
		color:#fff;
	}
	@media only screen and (max-width: 768px) {
		.logo { width: 32%; }
	}
</style>


<!-- custom javascript -->
<script src="{{ asset('ui-ecommerce/js/script.js')}}" type="text/javascript"></script>

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
<br>
<!-- ========================= SECTION MAIN ========================= -->
@yield('content')
<!-- ========================= SECTION CONTENT END// ========================= -->

@yield('scripts')

<script>
	$(document).ready(function() {
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

		$('.notification-permission').on('click', function() {
			alert();
		})
    });
</script>
</body>
</html>
