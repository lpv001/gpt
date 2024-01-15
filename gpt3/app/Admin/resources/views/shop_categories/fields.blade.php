
<div class="form-group col-sm-12">
    {!! Form::label('user_id', 'English Name:') !!} <span class="text-danger">*</span>
    <div class="d-flex flex-row">
        <input type="hidden" name="locale[]" value="en">
        @if (old('name'))
            <input type="text" name="name[]" value="{{ old('name')[0] }}" id="" class="form-control" required>
        @else
            <input type="text" name="name[]" value="{{ @$shop_category_translate[0]->name ?? ''}}" id="" class="form-control" required>
        @endif
    </div>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('user_id', 'Khmer Name:') !!}
    <div class="d-flex flex-row">
        <input type="hidden" name="locale[]" value="km">
        @if (old('name'))
            <input type="text" name="name[]" value="{{ old('name')[1] }}" id="" class="form-control">
        @else
            <input type="text" name="name[]" value="{{ @$shop_category_translate[1]->name ?? ''}}" id="" class="form-control">
        @endif
    </div>
</div>


<div class="form-group col-sm-12">
    {!! Form::label('images', 'Images:') !!}
    <div class="col-sm px-0">
        <div class="input-images"></div>
    </div>
   
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('shop-category.index') !!}" class="btn btn-default">Cancel</a>
</div>


@section('scripts')
    
@endsection