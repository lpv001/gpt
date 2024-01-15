@extends('frontend::layouts.main')

<style>
    .tags-active {
        background-color: #006a4d;
        color: #fff;
    }
    .tags:hover {
        cursor: pointer;
        background-color: #006a4d;
        color: #fff;
    }
</style>

@section('content')
<div class="container my-3">

    {{-- <div class="error alert alert-danger">
        <div id="errorMessage"></div>
        {{-- {{dd($errors)}} --}}
       
        {{-- We are sorry. This payment method were under maintaining. --}}
    {{-- </div> --}}

    @if ($errors->any())
        <div class="error alert alert-danger">
            Please fill the information.
        </div>
    @endif

    <form action="{{ route('my.location.store') }}" method="post">
        @csrf
        <div class="row">
            
            <div class="col-6 pr-0">
                <div class="card p-3">
                    {{-- Map --}}
                    <div class="visible" style="">
                        <div id="map" class="map" style="width:100%; height:400px;">
                        
                        </div>
                    </div>
                    <input type="hidden" id="lat" name="lat" value="">
                    <input type="hidden" id="lng" name="lng" value="">
                </div>
            </div>
            {{-- end left side --}}

            {{-- right side --}}
            <div class="col-6 pr-0">
                <div class="card p-3">
                    <h5>{{ __('frontend.address') }}</h5>
                    <div class="my-2">
                        <div class="p-2 bg-light rounded adddress"></div>
                        <input type="hidden" name="address" value="" id="address">
                    </div>

                    <div class="my-3">
                        <div class="h5">
                            {{ __('frontend.note') }}:
                        </div>
                        <input type="text" class="form-control" name="note" id="note" placeholder="{{ __('frontend.street_number') }}">
                    </div>

                    <div class="my-3">
                        <div class="d-flex">
                            <h5>{{ __('frontend.save_location_as') }}<span class="text-danger">*</span></h5>
                            @if ($errors->any())
                                @if ($errors->first('tag'))
                                    <span class="ml-2 text-danger">{{ __('frontend.msg_select_one') }}</span>
                                @endif
                            @endif
                        </div>

                        <div class="d-flex my-3">
                            <div class="border px-3 py-2  mr-2 rounded  tags" data-show="0" data-value="work">
                                {{ __('frontend.work_location') }}
                            </div>

                            <div class="border px-3 py-2 mr-2 rounded  tags" data-show="0" data-value="home">
                                {{ __('frontend.home_location') }}
                            </div>

                            <div class="border px-3 py-2 mr-2 rounded tags" id="other" data-show="1" data-="other">
                                {{ __('frontend.other_location') }}
                            </div>
                        </div>
                    </div>

                    <div class="my-2 tag-box">
                        <input type="text" name="tag" class="form-control" id="" value="">
                    </div>

                    <button class="btn btn-lg btn-warning my-2">{{ __('frontend.save') }}</button>
                </div> {{-- end card --}}

            </div>  {{-- end col --}}
            {{-- end right side --}}
        </div>
    </form>
</div>



<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJDGDxEhNyb4rND-X7cKaYvcr0zLPYMGs&callback=initMap&libraries=&v=weekly&language=km"
    defer>
</script>
<script>
	var map, infoWindow;

    // $('.error-bag').hide();
    $('.tag-box').hide();

      function initMap() {
        var myLatlng = new google.maps.LatLng(11.5564, 104.9282);
        const geocoder = new google.maps.Geocoder();

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(
            function (position) {
                console.log(position.coords.latitude);
				document.getElementById("lat").value = position.coords.latitude;
				document.getElementById("lng").value = position.coords.longitude;
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude,
				};
				var mapOptions = {
					zoom: 18,
					center: pos
				}

				var map = new google.maps.Map(document.getElementById("map"), mapOptions);
                // Get geolocation
                geocodeLatLng(geocoder, pos);

				var marker = new google.maps.Marker({
					position: pos,
					map: map,
					animation: google.maps.Animation.DROP,
					draggable:true,
				});

				 // Add a "drag end" event handler
				 google.maps.event.addListener(marker, 'dragend', function() {
                    geocodeLatLng(geocoder, { lat : this.position.lat(), lng : this.position.lng() });
					document.getElementById("lat").value = this.position.lat();
					document.getElementById("lng").value = this.position.lng();
				});
            },
            function () {
              handleLocationError(true, infoWindow, map.getCenter());
            }
          );
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }

      function geocodeLatLng(geocoder, position) {
        let fullAddress = '';
        let addresses = [];
        geocoder.geocode({ location: position })
            .then((response) => {
                if (response.results.length > 0) {
                    addresses = response.results[0].address_components;
                } else {
                    window.alert("No results found");
                }
            })
            .catch(function(error) {
                // $('.error').show();
                // $('#errorMessage').text(error.message)
                console.log(error.message)
            }).finally(() => {
                $.each(addresses, function(key, address) {

                    fullAddress += `${address.long_name}${key == addresses.length -1 ? '' : ', '}`;
                })
                
                $('.adddress').html(fullAddress);
                $('#address').val(fullAddress);
            });

        
    }

	  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(
          browserHasGeolocation
            ? "Error: The Geolocation service failed."
            : "Error: Your browser doesn't support geolocation."
        );
        infoWindow.open(map);
	  }

    $('.tags').on('click', function() {
        $('.tags').removeClass('tags-active');
        $(this).addClass('tags-active');

        $('[name=tag]').val($(this).data('value'))

        if($(this).data('show') == 1) {
            $('.tag-box').show();
        } else {
            $('.tag-box').hide();
        }
    })
</script>
@endsection