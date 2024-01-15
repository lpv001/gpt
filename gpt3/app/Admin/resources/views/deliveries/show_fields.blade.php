<div class="form-group row">
  <!-- Name Field -->
  <div class="col-sm-6">
      {!! Form::label('Name', 'Name:') !!}
      <p>{!! $delivery->name !!}</p>
  </div>
  <!-- Cost Field -->
  <div class="col-sm-6">
      {!! Form::label('Cost', 'Cost:') !!}
      <p>${!! $delivery->cost !!}</p>
  </div>
</div>

<div class="form-group row">
  <!-- City1 ID Field -->
  <div class="col-sm-6">
      {!! Form::label('city_id1', 'From City:') !!} <br>
      <p>{!! \App\Admin\Models\City::find($delivery->city_id1)->default_name !!}</p>
  </div>
  <!-- City2 ID Field -->
  <div class="col-sm-6">
      {!! Form::label('city_id2', 'To City:') !!} <br>
       <p>{!! \App\Admin\Models\City::find($delivery->city_id2)->default_name !!}</p>
  </div>
</div>

<div class="form-group row">
  <!-- Min Distance Field -->
  <div class="col-sm-6">
      {!! Form::label('min_distance', 'Min Distance:') !!}
      <p>{!! $delivery->min_distance !!}Km</p>
  </div>
  <!-- Min Distance Field -->
  <div class="col-sm-6">
      {!! Form::label('max_distance', 'Max Distance:') !!}
      <p>{!! $delivery->max_distance !!}Km</p>
  </div>
</div>

<div class="form-group row">
  <!-- Provider Id Field -->
  <div class="col-sm-5">
      {!! Form::label('provider_id', 'Provider:') !!}
      <p>{!! \App\Admin\Models\DeliveryProvider::find($delivery->provider_id)->name !!}</p>
  </div>
  <!-- Is Active Field -->
  <div class="col-sm-3">
      {!! Form::label('is_active', 'Is Active:') !!}<br />
      <p>{!! $delivery->is_active !!}</p>
  </div>
  <!-- Submit Field -->
  <div class="col-sm-4">
      <br />
      <a href="{!! route('deliveries.edit', [$delivery->id]) !!}" class="btn btn-primary">Edit</a>
  </div>
</div>

