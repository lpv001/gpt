<div class="container mt-3">
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Title <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            {!! Form::text('title', null, ['class' => 'form-control', 'required']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Target URL <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            {!! Form::text('target_url', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Expire Date <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            {!! Form::text('expiry_date', null, ['class' => 'form-control','id'=>'expiry_date', 'required', 'autocomplete'=> 'off']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Description <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Image <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            <div class="input-images"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Is Active</label>
        <div class="col-sm-10">
                <label class="checkbox-inline">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', '1', null) !!} 1
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

@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
       
        $( function() {
    $( "#expiry_date" ).datepicker();
  } );
    </script>
@endsection