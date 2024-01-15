<div class="form-group col-sm-1 pull-right">
    {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
</div>

<div class="form-group col-sm-2 pull-right">
{!! Form::select('city_id', $city, null , ['class' => 'form-control', 'placeholder' => 'City']) !!}
</div>

<div class="form-group col-sm-2 pull-right">
{!! Form::select('membership_id', $membership, null , ['class' => 'form-control', 'placeholder' => 'Type']) !!}
</div>

<div class="form-group col-sm-3 pull-right">
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Search']) !!}
</div>

