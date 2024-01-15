<!-- Product Id Field -->
<div class="form-group">
    {!! Form::label('product_id', 'Product Id:') !!}
    <p>{!! @\App\Admin\Models\Product::find($productPrice->product_id)->name !!}</p>
</div>

<!-- Type Id Field -->
<div class="form-group">
    {!! Form::label('type_id', 'Type Id:') !!}
    <p>{!! @\App\Admin\Models\Membership::find($productPrice->type_id)->name !!}</p>
</div>

<!-- Unit Id Field -->
<div class="form-group">
    {!! Form::label('unit_id', 'Unit Id:') !!}
    <p>{!! @\App\Admin\Models\Unit::find($productPrice->unit_id)->name !!}</p>
</div>

<!-- City Id Field -->
<div class="form-group">
    {!! Form::label('city_id', 'City Id:') !!}
    <p>{!! @\App\Admin\Models\City::find($productPrice->city_id)->default_name !!}</p>
</div>

<!-- Unit Price Field -->
<div class="form-group">
    {!! Form::label('unit_price', 'Unit Price:') !!}
    <p>{!! $productPrice->unit_price !!}</p>
</div>

<!-- Sale Price Field -->
<div class="form-group">
    {!! Form::label('sale_price', 'Sale Price:') !!}
    <p>{!! $productPrice->sale_price !!}</p>
</div>

<!-- Is Active Field -->
<div class="form-group">
    {!! Form::label('is_active', 'Is Active:') !!}
    <p>{!! $productPrice->is_active !!}</p>
</div>

