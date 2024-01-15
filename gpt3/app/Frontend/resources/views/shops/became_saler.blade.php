@extends('frontend::layouts.main')

@section('content')
<section class="section-content padding-y">
	<div class="container">
	{{ Form::open(['route' => 'my_account.becameseller', 'method' => 'post', 'files' => true, 'enctype'=>'multipart/form-data', 'id' => 'becamesaler']) }}
		@csrf
		<!-- Name Field -->
		<div class="form-group col-sm-6">
			{!! Form::label('name', 'Shop Name:') !!}
			{!! Form::text('name', null, ['class' => 'form-control col-sm-10', 'required' => 'required']) !!}
		</div>
		<!-- Name Field -->
		<div class="form-group col-sm-6">
			{!! Form::label('phone', 'Phone:') !!}
			{!! Form::text('phone', null, ['id' => 'phone', 'class' => 'form-control col-sm-10', 'required' => 'required']) !!}
		</div>

		<div class="form-group col-sm-6">
			{!! Form::label('membership_id', 'Membership:') !!}
			{!! Form::select('membership_id', $membership, [], ['id'=>'membership', 'class' => 'form-control col-sm-10', 'required' => 'required']) !!}
		</div>

		<div class="form-group col-sm-6">
			{!! Form::label('city_province_id', 'City/Province:') !!}
			{!! Form::select('city_province_id', $city, [], ['id'=>'city', 'class' => 'form-control col-sm-10', 'required' => 'required']) !!}
		</div>

		<div class="form-group col-sm-6">
			{!! Form::label('district_id', 'District:') !!}
			{!! Form::select('district_id', [], [], ['id'=>'district', 'class' => 'form-control col-sm-10', 'required' => 'required']) !!}
		</div>

		<div class="form-group col-sm-6">
			{!! Form::label('address', 'Address:') !!}
			{!! Form::text('address', null, ['class' => 'form-control col-sm-10', 'required' => 'required']) !!}
		</div>

		<div class="form-group col-sm-6">
			{!! Form::label('supplier_id', 'Supplier:') !!}
			{!! Form::select('supplier_id', [], [], ['id'=>'supplier', 'class' => 'form-control col-sm-10', 'required' => 'required']) !!}

		</div>

		<div class="d-flex justify-content-start">
			<div class="form-group col-sm-4">
				{!! Form::label('lat', 'Latitute:') !!}
				{!! Form::text('lat', null, ['id'=>'lat', 'class' => 'form-control col-sm-12', 'required' => 'required']) !!}
			</div>

			<div class="form-group col-sm-4">
				{!! Form::label('lng', 'Longitude:') !!}
				{!! Form::text('lng', null, ['id'=>'lng', 'class' => 'form-control col-sm-12', 'required' => 'required']) !!}
			</div>
		</div>

		<!-- Logo and Cover image  -->
		<div class="d-flex justify-content-start">
			<div class="form-group col-sm-4">
				{!! Form::label('logo_image', 'Logo:') !!}
				{!! Form::file('logo_image', ['class' => 'form-control col-sm-12','accept'=>'image/*']) !!}
			</div>

			<div class="form-group col-sm-4">
				{!! Form::label('cover_image', 'Cover:') !!}
				{!! Form::file('cover_image', ['class' => 'form-control col-sm-12','accept'=>'image/*']) !!}
			</div>
		</div>

		<!-- Submit Field -->
		<div class="form-group col-sm-12">
			{!! Form::submit('Submit', ['class' => 'btn btn-warning']) !!}
		</div>

	{{ Form::close() }}
	</div>	
</section>

<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
	$(function() {
		$('#becamesaler').validate();

		$.fn.inputFilter = function(inputFilter) {
			return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
			if (inputFilter(this.value)) {
				this.oldValue = this.value;
				this.oldSelectionStart = this.selectionStart;
				this.oldSelectionEnd = this.selectionEnd;
			} else if (this.hasOwnProperty("oldValue")) {
				this.value = this.oldValue;
				this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
			} else {
				this.value = "";
			}
			});
		};
		// Validate input phone field 
		$("#phone").inputFilter(function(value) {
  			return /^-?\d*$/.test(value); 
  		});
		// Validate input lat field 
		$("#lat").inputFilter(function(value) {
  			return /^-?\d*[.,]?\d*$/.test(value); 
		});
		// Validate input lng field 
		$("#lng").inputFilter(function(value) {
  			return /^-?\d*[.,]?\d*$/.test(value); 
		});

		$('#membership').change(function(){
			$('#supplier').html('');
			// $('#supplier').attr('disabled', true);
		});

		let districtEle = $('#district');
		districtEle.html('').attr('disabled', true);
		$( "#city" ).change(function() {
			districtEle.html('').attr('disabled', false);
			var value = $(this).children("option:selected").val();
			let districtOption = $('#district');

			$.ajax({
				url: '/',
				success: function(data){
					var districtData = '';
					$.each(data , function( index, value ) {
						districtData += '<option value="' + index + '">' + value + '</option>';
					});
					districtEle.html(districtData);
				},
			});
		});	


		let supplierEle = $('#supplier');
		supplierEle.html('').attr('disabled', true);
		$( "#district" ).change(function() {
			var membership = $('#membership').find(":selected").val();
			var district_id = $(this).children("option:selected").val();
			supplierEle.html('').attr('disabled', false);
			
			$.ajax({
				url: '/',
				success: function(data){
					console.log(data)
					var eleData = '';
					$.each(data , function( index, value ) {
						eleData += '<option value="' + index + '">' + value + '</option>';
					});
					supplierEle.html(eleData);
				},
			});
		});			
	});	
</script>
@endsection
