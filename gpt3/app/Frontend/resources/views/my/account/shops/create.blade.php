<div class="row my-1 mb-3">
	<div class="col-6 col-sm-6">
		<label for="shopName" class="{{ @$errors->first('name') ? 'text-danger' : '' }}">{{ __('frontend.shop_name') }} <span class="text-danger">*</span></label>
		<input type="text" id="shopName" class="form-control" name="name" placeholder="{{ __('frontend.shop_name') }}" value="{{ old('name') ?? @$shop['name'] ?? '' }}" required>
	</div>
	<div class="col-6 col-sm-6">
		<label for="phone" class="{{ @$errors->first('phone') ? 'text-danger' : '' }}">{{ __('frontend.phone') }} 
			<span class="text-danger">*</span></label>
		<input type="text" id="phone" class="form-control" name="phone" placeholder="{{ __('frontend.phone_number') }}" value="{{ old('phone') ?? @$shop['phone'] ?? '' }}" required>
	</div>
</div>

{{-- {{dd(auth()->user())}} --}}
<div class="row my-1 mb-3">
	<div class="col-6 col-sm-6">
		<label for="membership" class="{{ @$errors->first('membership_id') ? 'text-danger' : '' }}">
			{{ __('frontend.membership_type') }} <span class="text-danger">*</span></label>
			
		{!! Form::select(
			'membership_id', @$memberships ?? [], old('membership_id') ?? @$shop['membership_id'] ?? '', 
			[
				'id'=>'membership', 
				'placeholder' => __('frontend.select_membership'),
				'class' => 'form-control', 
				'required' => 'required'
			]) !!}
	</div>
	<div class="col-6 col-sm-6">
		<label for="supplier"  class="{{ @$errors->first('supplier_id') ? 'text-danger' : '' }}">
			{{ __('frontend.supplier') }} <span class="text-danger">*</span></label>
		{!! Form::select(
			'supplier_id', 
			@$shop ? [$shop['supplier_id'] => $shop['supplier_name']] : [], @$shop['supplier_id'] ?? [], 
			[
				'id'=>'supplier', 
				'placeholder' => __('frontend.select_supplier'),
				'class' => 'form-control ', 
				'required' => 'required'
			]) !!}
	</div>
</div>
<div class="row my-1 mb-3">
	<div class="col-6 col-sm-6">
		<label for="city_province_id" class="{{ @$errors->first('city_province_id') ? 'text-danger' : '' }}">
			{{ __('frontend.city_province') }} <span class="text-danger">*</span></label>
		{!! Form::select(
			'city_province_id', @$cities ?? [], old('city_province_id') ??  @$shop['city_province_id'] ?? '', 
			[
				'id'=>'city', 
				'placeholder' => __('frontend.select_city'), 
				'class' => 'form-control ', 
				'required' => 'required'
			]) !!}
	</div>
	<div class="col-6 col-sm-6">
		<label for="district" class="{{ @$errors->first('district_id') ? 'text-danger' : '' }}">
			{{ __('frontend.disctrict') }} <span class="text-danger">*</span></label>
		{!! Form::select(
			'district_id', @$districts ?? [], @$shop['district_id'] ?? '', 
			[
				'id'=>'district', 
				'placeholder' => __('frontend.select_district'),
				'class' => 'form-control ', 
				'required' => 'required'
			]) !!}
	</div>
</div>

<div class="row my-1 mb-3">
	<div class="col-6 col-sm-6">
		<label for="address" class="{{ @$errors->first('address') ? 'text-danger' : '' }}">
			{{ __('frontend.address') }} <span class="text-danger">*</span></label>
		<input type="text" id="address" class="form-control" name="address">
	</div>

	<div class="col-6 col-sm-6">
		<label for="shop_category" class="{{ @$errors->first('shop_category_id') ? 'text-danger' : '' }}">
			{{ __('frontend.shop_category') }} <span class="text-danger">*</span></label>
			
		{!! Form::select(
			'shop_category_id', @$shop_categories ?? [], old('shop_category_id') ?? @$shop['shop_category_id'] ?? '', 
			[
				'id'=>'shop_category_id', 
				'placeholder' => __('frontend.select_shop_category'),
				'class' => 'form-control', 
				'required' => 'required'
			]) !!}
	</div>
</div>

<div class="row">
	<div class="col-6 col-sm-6">
		<label for="logo" class="{{ @$errors->first('logo_image') ? 'text-danger' : '' }}">{{ __('frontend.shop_logo') }}
		 <span class="text-danger">*</span>
		</label>

		@if (@$shop)
			<img src="{{ @$shop['logo_image'] }}" class="shop-images" alt="logo">
		@else
			<input type="file" class="form-control" id="logo" name="logo_image">
		@endif
	</div>
	<div class="col-6 col-sm-6">
		<label for="cover" class="{{ @$errors->first('cover_image') ? 'text-danger' : '' }}">{{ __('frontend.shop_cover_profile_image') }}
			<span class="text-danger">*</span>
		</label>
		@if (@$shop)
			<img src="{{ @$shop['cover_image'] }}" class="shop-images" alt="cover">
		@else
			<input type="file" name="cover_image" id="cover" class="form-control">
		@endif
	</div>
</div>

<div class="row">
	<div class="col">
		<div id="map">
			
		</div>
	</div>
</div>

<input type="hidden" id="lat" name="lat" value="0">
<input type="hidden" id="lng" name="lng" value="0">
<input type="hidden" name="country_id" value="1" >

<div class="row my-3">
	@if (!@$shop)
	<div class="col-12 text-right">
		<button type="submit" class="btn btn-xs btn-warning px-4 ">{{ __('frontend.open_shop') }}</button>
	</div>
	@endif
	
</div>
