<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Icon Field -->
<!-- Cover Image Field -->
<div class="form-group col-sm-6">
<div class="form-group col-sm-6">
  @if(@$edit && $deliveryProvider->icon != "")
      <table class="table table-bordered">
        <tr>
          <td>Icon</td>
          <td>Image name</td>
          <td>Delete</td>
        </tr>
        <tr>
          <td><img src="{{ asset('uploads/images/shops/'. $deliveryProvider->icon)}}" width="100px" /></td>
          <td>{{ $deliveryProvider->icon }}</td>
          <td>{!! Form::checkbox('chk_delete', $deliveryProvider->id, null) !!}</td>
        </tr>
      </table>
  @else
  {!! Form::label('icon', 'icon:') !!}
  {!! Form::file('icon', ['class' => 'form-control','accept'=>'image/*']) !!}
  @endif
</div>

<!-- Cost Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cost', 'Cost:') !!}
    {!! Form::number('cost', null, ['class' => 'form-control']) !!}
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
    <a href="{!! route('deliveryProviders.index') !!}" class="btn btn-default">Cancel</a>
</div>
