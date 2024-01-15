<div class="container mt-3">

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Parent</label>
        <div class="col-sm-10">
            {!! Form::select('parent_id', $units, @$unit->parent_id ?? [], ['placeholder' => 'Pick Parent', 'class' => 'form-control select2']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Name <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            {{-- {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!} --}}
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
                    @if (@$unit_translateion)
                        @foreach (@$unit_translateion as $key => $item)
                            @if ($key === 'en' || $key === 'km')
                                <tr>
                                    <td class="py-4">1</td>
                                    <td>
                                        {!! Form::select('lang_', ['en' => 'English', 'km' => 'Khmer', 'cn' => 'China'], @$key, ['class' => 'form-control select2', 'required', 'disabled']) !!}
                                        {!! Form::hidden('lang[]', @$key, []) !!}
                                        {{-- @if ($key === 'en')
                                            {!! Form::select('lang_', ['en' => 'English', 'km' => 'Khmer', 'cn' => 'China'], 'en', ['class' => 'form-control select2', 'required', 'disabled']) !!}
                                            {!! Form::hidden('lang[]', 'en', []) !!}
                                        @else
                                            {!! Form::select('lang[]', ['km' => 'Khmer', 'cn' => 'China'], @$key ?? '', ['class' => 'form-control select2', 'required']) !!}
                                        @endif --}}
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" value="{{@$item}}" name="name[]" required>
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
                                <input class="form-control" type="text" value="" name="name[]" required>
                            </td>
                            <td><a class="btn btn-sm btn-light" disabled><i class="fa fa-minus"></i></a></td>
                        </tr> 
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Description</label>
        <div class="col-sm-10">
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
        </div>
    </div> --}}

    <!-- Submit Field -->
    <div class="for-group row">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-10">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('products.index') !!}" class="btn btn-default">Cancel</a>
        </div>
    </div>
</div>

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
                        <input class="form-control" type="text" value="" name="name[]" required>
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