<div class="row mx-2 my-2">
    <div class="col-sm bg-warning">
        @if($errors->any())
            <p class="text-white">{{$errors->first()}}</p>
        @endif
    </div>
</div>

<div class="row mx-2 my-2">
    <div class="col-sm">
      <p class="mb-1">{{ __('frontend.shop_name') }} <span class="text-danger">*</span></p>
      <input type="text" class="form-control" name="name" placeholder="Enter name" required>
    </div>
</div>

<div class="row mx-2 my-2">
<div class="col-sm">
  <p class="mb-1">{{ __('frontend.phone') }} <span class="text-danger">*</span></p>
  <input type="text" class="form-control" name="phone" placeholder="Enter phone" required maxlength="13" minlength="6">
</div>
</div>

<div class="row mx-2 my-2">
<div class="col-sm">
  <p class="mb-1">{{ __('frontend.membership') }} <span class="text-danger">*</span> <span class="error"></span></p>
  {!! Form::select('membership_id', @$membership, [], ['id'=>'membership', 'class' => 'form-control select2', 'required' => 'required']) !!}
</div>
</div>

<div class="row mx-2 my-2">
<div class="col-sm">
<p class="mb-1">{{ __('frontend.city_or_province') }} <span class="text-danger">*</span> <span class="error text-danger"></span></p></p> 
{!! Form::select('city_province_id', @$city, [], ['placeholder' => 'Select City','id'=>'city', 'class' => 'form-control select2', 'required' => 'required']) !!}

</div>
</div>

<div class="row mx-2 my-2">
<div class="col-sm">
<p class="mb-1">{{ __('frontend.disctrict') }} <span class="text-danger">*</span> <span class="error"></span> </p>
{!! Form::select('district_id', @$district, [], ['placeholder' => 'Select District','id'=>'district', 'class' => 'form-control select2', 'required' => 'required']) !!}

</div>
</div>

<div class="row mx-2 my-2">
<div class="col-sm">
<p class="mb-1">{{ __('frontend.supplier') }} <span class="text-danger">*</span></p>
{!! Form::select('supplier_id', [], [], ['id'=>'supplier', 'class' => 'form-control', 'required' => 'required']) !!}

</div>
</div>

<div class="row mx-2 my-2">
<div class="col-sm">
<p class="mb-1">{{ __('frontend.address') }} <span class="text-danger">*</span></p>
<textarea name="address" class="form-control" cols="30" rows="4"></textarea>
</div>
</div>

<div class="row mx-2 my-2">
<div class="col-sm">
<p class="mb-1">{{ __('frontend.logo_image') }} <span class="text-danger">*</span></p>
<input type="file" class="form-control" name="logo" placeholder="Enter name" required>
</div>
<div class="col-sm">
<p class="mb-1">{{ __('frontend.cover_image') }}</p>
<input type="file" class="form-control" name="cover" placeholder="Enter name" required>
</div>
</div>

<div class="row mx-2 my-2">
<div class="col-sm">
    <input type="hidden"  name="lat" value="">
    <input type="hidden" name="lng" value="">
{{-- <p class="mb-1">{{ __('frontend.map') }}</p> --}}
{{-- <input type="text" class="form-control" name="" placeholder="Enter name"> --}}
</div>
</div>

{{-- <hr> --}}
<div class="row mx-2 my-2">
    <div class="col-sm">
        <button class="btn btn-xs btn-primary"> Submit</button>
        {{-- <a class="btn btn-xs btn-primary"> C</a> --}}
    </div>
</div>

@section('scripts')
    <script>
        $(document).on('change', '#city', function() {
			$.get(`/my-account/myshop-get-discrtict/${$(this).val()}`, function(response) {
				let option = '<option value="" disabled selected>Select District</option>';
				if (response.status) {
					$.each(response.data, function(key, value) {
						option += `<option value="${key}">${value}</option>`;
					})
				}
				$('#district').html(option)
			});
		});


		$(document).on('change', '#district', function() {
			let membership = $('#membership').val();
			let city = $('#city').val();
			let district = $(this).val();

			$('.select2').each(function(key, element) {
				if (!element.value) {
					$(element).css("border", "1px solid red");
					$(element).closest('div').find('.error').html('( Please select )');
				} else {
					$(element).css("border", "1px solid #ced4da");
					$(element).closest('div').find('.error').html('');
				}
			});

			$.get(`/my-account/myshop-get-supplier`, {membership, city, district},  function(response) {
				console.log(response);
				let option = '<option value="" disabled selected>Select Supplier</option>';
				if (response.status) {
					$.each(response.data, function(key, value) {
						option += `<option value="${key}">${value}</option>`;
					})
				}
				$('#supplier').html(option)
			});
		});

        function success(position) {
            const latitude  = position.coords.latitude;
            const longitude = position.coords.longitude;

            $('[name="lat"]').val(latitude);
            $('[name="lng"]').val(longitude);

            console.log(latitude);
            console.log(longitude);
        }

        function error() {
            console.log('Unable to retrieve your location');
        }

        $(document).ready(function() {
            if(!navigator.geolocation) {
                console.log('Geolocation is not supported by your browser')
            } else {
                console.log('Locating…');
                navigator.geolocation.getCurrentPosition(success, error);
            }
        });
    </script>
@endsection