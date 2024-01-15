<div class="container mt-3">
    <div class="form-group row">
        @if (@$edit)
        <div class="col-12">
            @php
            $format_data = unserialize($code->format_data);
            @endphp
            Format of X1 is set to : {{$format_data['x1']}}
        </div>
        @else
        <label for="colFormLabelLg" class="col-4  col-form-label col-form-label-lg text-right">X1</label>
        <div class="col-8">
            {!! Form::select('setting_id', $settings, @$edit? $code->setting_id : [], ['placeholder' => 'Please select Setting', 'class' => 'form-control select2']) !!}
        </div>
        @endif
    </div>

    <div class="form-group row">
        @if (@$code->is_ready == 0)
        <label for="colFormLabelLg" class="col-sm-4  col-form-label col-form-label-lg text-right">Generate Code Data?</label>
        <div class="col-sm-8">
            <label class="checkbox-inline">
                {!! Form::checkbox('be_generated', '1', null) !!}
            </label>
        </div>
        @endif
        
        @if (@$code->is_ready == 1)
        <label for="colFormLabelLg" class="col-12  col-form-label col-form-label-lg">Code data generation is in progress...</label>
        @endif
        
        @if (@$code->is_ready == 2)
        <a href="{!! route('codes.download', $code->id) !!}" class="btn btn-primary">Download Code Data</a>
        @endif
    </div>

    <!-- Submit Field -->
    <div class="for-group row">
        <div class="col-2"></div>
        <div class="form-group col-10">
            {{-- {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!} --}}
            <button type="submit" class="btn btn-primary " id="submit">Save</button>
            <a href="{!! route('codes.index') !!}" class="btn btn-default">Cancel</a>

        </div>
    </div>
</div>
