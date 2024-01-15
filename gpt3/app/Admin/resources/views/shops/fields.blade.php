<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User:') !!}
    <div class="d-flex flex-row">
        {!! Form::select('user_ids[]',$users, @$edit? $shop->user_ids : [], ['placeholder' => 'Please select users', 'class' => 'form-control select2 users', 'required', 'multiple' => 'multiple']) !!}
        <span class="btn btn-sm btn-primary create-user" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-plus"></i></span>
    </div>
</div>

<!-- Supplier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('supplier_id', 'Supplier:') !!}
    {!! Form::select('supplier_id', $shops , @$edit? $shop->supplier_id : [], ['placeholder' => '-- Please Select --','class' => 'form-control select2']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Shop Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

{{--}}
<!-- Country Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('country_id', 'Country:') !!}
    {!! Form::select('country_id',$countries, @$edit? $shop->country_id : 1, ['placeholder' => '-- Please Select --','class' => 'form-control select2']) !!}
</div>
--}}

<!-- City Province Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('city_province_id', 'City:') !!}
    {!! Form::select('city_province_id',$cities, @$edit? $shop->city_province_id : [], ['placeholder' => '-- Please Select --','class' => 'form-control select2 city']) !!}
</div>

<!-- District Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('district_id', 'District:') !!}
    {!! Form::select('district_id',$districts, @$edit? $shop->district_id : [], ['placeholder' => '-- Please Select --','class' => 'form-control select2 district']) !!}
</div>

<!-- Membership Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('membership_id', 'Membership:') !!}
    {!! Form::select('membership_id', $membership, @$edit? $shop->membership_id : [], ['placeholder' => '-- Please Select --','class' => 'form-control select2 ']) !!}
</div>

<div class="form-group col-sm-6">
  {!! Form::label('shop_category', 'Shop Catogory:') !!}
  <div class="d-flex flex-row">
      {!! Form::select('shop_category_id', $shop_category, @$edit? $shop->shop_category_id : [], ['placeholder' => '-- Please Select --','class' => 'form-control select2', 'id' => 'shop_category_id']) !!}
      <span class="btn btn-sm btn-primary create-user" data-toggle="modal" data-target="#shopCategory"><i class="fa fa-plus"></i></span>
  </div>
</div>

<!-- About Field -->
<div class="form-group col-sm-6">
  {!! Form::label('about', 'About:') !!}
  {!! Form::textarea('about', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
  <div class="row">
    <!-- Shop Status Field -->
    <div class="form-group col-sm-12">
      {!! Form::label('status', 'Approval:') !!}
      {!! Form::select('status', $status, @$edit? $shop->status : 1, ['class' => 'form-control select2']) !!}
    </div>
    <!-- Logo Image Field -->
    <div class="form-group col-sm-12">
      @if(@$edit && $shop->logo_image != "")
        <table class="table table-bordered">
          <tr>
            <td>Logo Image</td>
            <td>Image name</td>
            <td>Delete</td>
          </tr>
          <tr>
            <td><img src="{{ asset('uploads/images/shops/'. $shop->logo_image)}}" width="100px" /></td>
            <td>{{ $shop->logo_image }}</td>
            <td>{!! Form::checkbox('logo_image_delete', $shop->logo_image, null) !!}</td>
          </tr>
        </table>
      @else
        {!! Form::label('logo_image', 'Logo Image:') !!}
        {!!Form::file('logo_image', ['class' => 'form-control','accept'=>'image/*']) !!}
      @endif
    </div>

    <!-- Cover Image Field -->
    <div class="form-group col-sm-12">
      @if(@$edit && $shop->cover_image != "")
          <table class="table table-bordered">
            <tr>
              <td>Cover Image</td>
              <td>Image name</td>
              <td>Delete</td>
            </tr>
            <tr>
              <td><img src="{{ asset('uploads/images/shops/'. $shop->cover_image)}}" width="100px" /></td>
              <td>{{ $shop->cover_image }}</td>
              <td>{!! Form::checkbox('cover_image_delete', $shop->cover_image, null) !!}</td>
            </tr>
          </table>
      @else
      {!! Form::label('cover_image', 'Cover Image:') !!}
      {!! Form::file('cover_image', ['class' => 'form-control','accept'=>'image/*']) !!}
      @endif
    </div>
  </div>
</div>

<!-- Address Field -->
<div class="form-group col-sm-12">
  {!! Form::label('address', 'Address:') !!}
  <!--{!! Form::textarea('address', null, ['class' => 'form-control']) !!}-->
  <input type="text" id="address" class="form-control" name="address" value="{{ @$edit ? $shop->address : '' }}">
</div>

<!-- Map -->
<div class="form-group col-sm-12">
  <div class="visible" style="">
    <div id="map" class="map" style="width:100%; height:350px;">
    </div>
  </div>
  <input type="hidden" id="lat" name="lat" value="{{ @$edit ? $shop->lat : 0 }}">
  <input type="hidden" id="lng" name="lng" value="{{ @$edit ? $shop->lng : 0 }}">
  <input type="hidden" id="city_id" name="city_id" value="{{ @$edit ? $shop->city_id : 0 }}">
  <input type="hidden" id="country_id" name="country_id" value="{{ @$edit ? $shop->country_id : 1 }}">
</div>

<!-- Is Active Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_active', 'Is Active:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('is_active', 0) !!}
        {!! Form::checkbox('is_active', '1', null) !!} 
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('shops.index') !!}" class="btn btn-default">Cancel</a>
</div>


<!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Create User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Name <span class="text-danger">*</span> <span id="username-error" class="text-danger ml-2"></span></label>
                <input type="text" class="form-control" id="username" aria-describedby="emailHelp" placeholder="Enter username">
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1" class="required">Phone <span class="text-danger">*</span> <span id="phone-error" class="text-danger ml-2"></span></label>
                <input type="text" class="form-control number-only" id="userphone" aria-describedby="emailHelp" placeholder="Enter phone">
            </div>
           
            <div class="form-group">
                <label for="exampleInputPassword1">Password <span class="text-danger">*</span> <span id="password-error" class="text-danger ml-2"></span></label>
                <input type="password" class="form-control" id="userpassword" placeholder="Password">
            </div>
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-secondary" data-dismiss="modal">Close</a>
          <a type="button" id="modelCreate" class="btn btn-primary">Save</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="shopCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Create Shop Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="exampleInputEmail1">English Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="shopCategoryNameEn" aria-describedby="emailHelp" placeholder="Enter name">
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1" class="required">Khmer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="shopCategoryNameKm" aria-describedby="emailHelp" placeholder="Enter name">
            </div>
           
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-secondary" data-dismiss="modal">Close</a>
          <a type="button" id="createShopCategory" class="btn btn-primary">Save</a>
        </div>
      </div>
    </div>
  </div>


@section('scripts')

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJDGDxEhNyb4rND-X7cKaYvcr0zLPYMGs&callback=initMap&libraries=&v=weekly&language=km"
    defer>
</script>

<script>
var map, infoWindow;

// Init the map
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

// Get Lat Lng from map
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
            for (var j = 0; j < address.types.length; j += 1) {
              if (address.types[j] === 'administrative_area_level_1') {
                console.log(address.short_name);
              }
            }
            fullAddress += `${address.long_name}${key == addresses.length -1 ? '' : ', '}`;
          })
          
          $('.adddress').html(fullAddress);
          $('#address').val(fullAddress);
      });
}

// Handle error
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}

</script>

    <script>
        $(document).on('change', '.city', function(){
            var city = $(this).val();
            var url  = "{{ route('get.district', ['id']) }}";
            var district = $('.district');

            $.get( url.replace('id', city), function(response) {
                if ( response.status != 200)
                    return;

                let option = '';
                $.each(response.data, function(key, value) {
                    option += `<option value="${key}">${value}</option>`;
                });

                $('.district').html(option);           
            });
        });

        $(document).on('change', '#user_id', function(){
            var value = $(this).val();
            var url  = "{{ route('users.show', ['id']) }}";

            $.get( url.replace('id', value), function(response) {
                if ( response.status != 200) 
                    swal("Fail!", response.message, "error");
                
                    $('#phone').val(response.data.phone);
            });
        });

        $(document).on('click', '#modelCreate',function(){
            var full_name = $('#username').val();
            var phone = $('#userphone').val();
            var password = $('#userpassword').val();

            if (full_name == '' || full_name.length < 3 ) {
                $('#username').focus();
                $('#username').addClass('is-invalid');
                return;
            }
            $('#username').removeClass('is-invalid');

            if ( phone == '' || phone.length < 7) {
                $('#userphone').focus();
                $('#userphone').addClass('is-invalid');
                return;
            }
            $('#userphone').removeClass('is-invalid');

            if ( password == '' || password.length < 6) {
                $('#userpassword').focus();
                $('#userpassword').addClass('is-invalid');
                return;
            }
            $('#userpassword').removeClass('is-invalid');

            
            $.ajax({
                url: "{{ route('users.store') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}", 
                    full_name:full_name,
                    phone:phone,
                    password:password,
                },
                dataType: 'JSON',
                success: function (response) { 
                   if (response.status != 201)
                    return;

                    let option = '';
                    $.each(response.data, function(key, value) {
                        option += `<option value="${value.id}" ${key == 0 ? 'selected' : ''}>${value.full_name}</option>`;
                    });

                    $('.users').html(option);
                    $('select').select2();
                    $('#phone').val(response.phone);
                    $('#exampleModalCenter').modal('hide')
                }, 
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr);
                    let error = JSON.parse(xhr.responseText);
                    $.each(error.errors, function(key, value) {
                       if(key == 'full_name') $('#username-error').text(`( ${value[0]} )`);
                       if(key == 'phone') $('#phone-error').text(`( ${value[0]} )`);
                       if(key == 'password') $('#password-error').text(`( ${value[0]} )`);
                    });
                    
                    console.log(error.errors);
                }
            }); 
        });

        $(document).on('change', '[name="supplier_id"]', function(){
            let route =  "{{ route('shops.get_supplier', ['id']) }}";

            $.get(route.replace('id', $(this).val()), function(response) {
                if (response.status == 200){
                    $('[name="city_province_id"]').val(response.data.supplier.city_province_id).trigger("change");
                    let option = '<option value="" disabled selected>-- Please Select --</option>';
                    // console.log();
                    if (response.data.membership) {
                        $('[name="membership_id"]').html(`<option value="${response.data.membership.id}" selected>${response.data.membership.name}</option>`)
                    } else {
                        $('[name="membership_id"]').html(``)
                    }

                    setTimeout(function() {
                        console.log(response.data.supplier.district_id);
                        $('[name="district_id"]').val(response.data.supplier.district_id).trigger("change");
                    }, 1000);

                    //set defualt lat and lng to shop by supplier lat and lng
                    $('[name="lat"]').val(response.data.supplier.lat);
                    $('[name="lng"]').val(response.data.supplier.lng);
                    $('[name="supplier_lat"]').val(response.data.supplier.lat);
                    $('[name="supplier_lng"]').val(response.data.supplier.lng);
                }
            });
        });

        $(document).on('click', '#createShopCategory', function() {
            let name_en = $('#shopCategoryNameEn').val();
            let name_km = $('#shopCategoryNameKm').val();
            // let selectEle = $('#shop_category_id');
            // console.log(selectEle)

            // return ;
            if (!name_en) {
                $('#shopCategoryNameEn').addClass('is-invalid');
                $('#shopCategoryNameEn').focus();
                return;
            }
            $('#shopCategoryNameEn').removeClass('is-invalid');

            $.ajax({
                url: "{{ route('shop-category.store') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}", 
                    name : [name_en, name_km],
                    locale: ['en', 'km']
                },
                dataType: 'JSON',
                success: function (response) { 
                    let option = '<option value="" disabled>-- Please Select --</option>';
                    if (response.status) {
                            $.each(response.data, function (key, value) {
                                option += `<option value="${value.shop_category_id}" ${ key == 0 ? 'selected' : '' }>${value.name}</option>`;
                            });
                   }
                   $('#shop_category_id').html('');
                   $('#shop_category_id').html(option);
                   $('#shopCategory').modal('hide')
                }
            }); 
        });
    </script>

@endsection
