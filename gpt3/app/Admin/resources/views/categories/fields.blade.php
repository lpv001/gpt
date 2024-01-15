<div class="container mt-3">

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Parent <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        {!! Form::select('parent_id', $categories ,@$edit? $category->parent_id : 0, ['placeholder' => 'Please select category','class' => 'form-control select2']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Name <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        {{-- {!! Form::text('default_name', null, ['class' => 'form-control', 'required']) !!} --}}
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col" width="2%">#</th>
                    <th scope="col" width="25%">Language <span class="text-danger">*</span></th>
                    <th scope="col" width="">Name <span class="text-danger">*</span></th>
                    <th scope="col" width="10%"><span class=" pull-left"><a href="#" class="btn btn-sm btn-primary pull-right add-more"><i class="fa fa-plus"></i></a></th>
                </tr>
            </thead>
            <tbody>
                @if (@$category_translation)
                    @foreach (@$category_translation as $key => $item)
                        @if ($key === 'en' || $key === 'km')
                            <tr>
                                <td class="py-4">1</td>
                                <td>
                                    {!! Form::select('lang_', ['en' => 'English', 'km' => 'Khmer', 'cn' => 'China'], @$key, ['class' => 'form-control select2', 'required', 'disabled']) !!}
                                    {!! Form::hidden('lang[]', @$key, []) !!}
                                </td>
                                <td>
                                    <input class="form-control" type="text" value="{{@$item}}" name="default_name[]" required>
                                </td>
                                <td><a class="btn btn-sm btn-light" disabled><i class="fa fa-minus"></i></a></td>
                            </tr> 
                        @endif
                        
                    @endforeach
                @else
                    <tr>
                        <td class="py-4">1</td>
                        <td>
                            {!! Form::select('lang_', ['en' => 'English', 'km' => 'Khmer', 'cn' => 'China'], 'en', ['class' => 'form-control select2', 'required', 'disabled']) !!}
                            {!! Form::hidden('lang[]', 'en', []) !!}
                        </td>
                        <td>
                            <input class="form-control" type="text" value="" name="default_name[]" required>
                        </td>
                        <td><a class="btn btn-sm btn-light" disabled><i class="fa fa-minus"></i></a></td>
                    </tr> 
                @endif
            </tbody>
        </table>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Slug</label>
        <div class="col-sm-10">
        {!! Form::text('slug', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Image</label>
        <div class="col-sm-10">
            <div class="input-images"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Is Active</label>
        <div class="col-sm-10">
            <label class="checkbox-inline">
            {!! Form::hidden('is_active', 1) !!}
            {!! Form::checkbox('is_active', '', null) !!} 1
        </label>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right"></label>
        <div class="col-sm-10">
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('categories.index') !!}" class="btn btn-default">Cancel</a>
        </div>
    </div>

</div>

{{--}}
<!-- Lft Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lft', 'Lft:') !!}
    {!! Form::number('lft', null, ['class' => 'form-control']) !!}
</div>

<!-- Rgt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rgt', 'Rgt:') !!}
    {!! Form::number('rgt', null, ['class' => 'form-control']) !!}
</div>

<!-- Depth Field -->
<div class="form-group col-sm-6">
    {!! Form::label('depth', 'Depth:') !!}
    {!! Form::number('depth', null, ['class' => 'form-control']) !!}
</div>

--}}
<!-- Order Field -->
{{--}}
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Order:') !!}
    {!! Form::number('order', null, ['class' => 'form-control']) !!}
</div>
--}}




<!-- Submit Field -->
<!-- <div class="form-group col-sm-12">
   
</div> -->

@section('scripts')
    <script>
        $(document).on('click', '.add-more', function() {
            let row = $('table tbody tr').length;   
            if (row >= 3 )
                return;
            // console.log(row);

            $('table tbody').append(`
                <tr>
                    <td class="py-4">1</td>
                    <td>
                        {!! Form::select('lang[]', ['en' => 'English','km' => 'Khmer', 'cn' => 'China'], '', ['placeholder' => 'Select Langauge','class' => 'form-control select2', 'required']) !!}
                    </td>
                    <td>
                        <input class="form-control" type="text" value="" name="default_name[]" required>
                    </td>
                    <td><a class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
                </tr>
            `);
            $('select').select2();
        });


        $(document).on('click', '.btn-danger', function() {
            $(this).closest('tr').remove();
        });
    </script>
@endsection
