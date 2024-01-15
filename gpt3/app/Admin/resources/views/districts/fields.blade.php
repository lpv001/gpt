<!-- Iso Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('iso_code', 'Iso Code:') !!}
    {!! Form::text('iso_code', null, ['class' => 'form-control']) !!}
</div>

<!-- City Province Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('city_province_id', 'City Province Id:') !!}
    {!! Form::number('city_province_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Default Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('default_name', 'Default Name:') !!}
    {!! Form::text('default_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6">
    {!! Form::label('slug', 'Slug:') !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
</div>

<!-- Lat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lat', 'Lat:') !!}
    {!! Form::number('lat', null, ['class' => 'form-control']) !!}
</div>

<!-- Lng Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lng', 'Lng:') !!}
    {!! Form::number('lng', null, ['class' => 'form-control']) !!}
</div>

<!-- Order Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Order:') !!}
    {!! Form::number('order', null, ['class' => 'form-control']) !!}
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
    <a href="{!! route('districts.index') !!}" class="btn btn-default">Cancel</a>
</div>
