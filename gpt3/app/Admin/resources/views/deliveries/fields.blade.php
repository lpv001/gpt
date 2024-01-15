<div class="form-group row">
  <!-- Name Field -->
  <div class="col-sm-6">
      {!! Form::label('Name', 'Name:') !!}
      {!! Form::text('name', null, ['class' => 'form-control']) !!}
  </div>
  <!-- Cost Field -->
  <div class="col-sm-6">
      {!! Form::label('Cost', 'Cost:') !!}
      {!! Form::text('cost', null, ['class' => 'form-control']) !!}
  </div>
</div>

<div class="form-group row">
  <!-- City1 ID Field -->
  <div class="col-sm-6">
      {!! Form::label('city_id1', 'From City:') !!} <br>
      {!! Form::select('city_id1', $cities , @$edit? $delivery->city_id1 : [], ['placeholder' => '-- Please Select --','class' => 'form-control from city', 'style' => 'width:100%']) !!}
  </div>
  <!-- City2 ID Field -->
  <div class="col-sm-6">
      @php
      $cities[0] = 'Any City';
      @endphp
      {!! Form::label('city_id2', 'To City:') !!} <br>
      {!! Form::select('city_id2', $cities , @$edit? $delivery->city_id2 : [], ['placeholder' => '-- Please select --','class' => 'form-control select to city', 'style' => 'width:100%']) !!}
  </div>
</div>

<div class="form-group row">
  <!-- Min Distance Field -->
  <div class="col-sm-6">
      {!! Form::label('min_distance', 'Min Distance:') !!}
      {!! Form::text('min_distance', null, ['class' => 'form-control']) !!}
  </div>
  <!-- Min Distance Field -->
  <div class="col-sm-6">
      {!! Form::label('max_distance', 'Max Distance:') !!}
      {!! Form::text('max_distance', null, ['class' => 'form-control']) !!}
  </div>
</div>

<div class="form-group row">
  <!-- Provider Id Field -->
  <div class="col-sm-5">
      {!! Form::label('provider_id', 'Provider:') !!}
      {!! Form::select('provider_id',$deliveryProvider , @$edit? $delivery->provider_id : [], ['placeholder' => '-- Please Select --','class' => 'form-control select2', 'style' => 'width:100%']) !!}
  </div>
  <!-- Is Active Field -->
  <div class="col-sm-3">
      {!! Form::label('is_active', 'Is Active:') !!}<br />
      <label class="checkbox-inline">
          {!! Form::hidden('is_active', 0) !!}
          {!! Form::checkbox('is_active', '1', null) !!}
      </label>
  </div>
  <!-- Submit Field -->
  <div class="col-sm-4">
      <br />
      {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
      <a href="{!! route('deliveries.index') !!}" class="btn btn-default">Cancel</a>
  </div>
</div>
