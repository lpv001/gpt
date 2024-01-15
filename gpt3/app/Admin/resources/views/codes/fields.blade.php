@if (session('error'))
<div class="row">
    <div class="col-sm-12">
        @include('admin::codes.fields_error')
    </div>
</div>
@endif

<!-- Code form Fields -->
<div class="row">
    <div class="col-sm-12">
        @include('admin::codes.fields_form')
    </div>
</div>

<!-- File prefix Field -->
<div class="form-group row">
    <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg">File Prefix</label>
    <div class="col-sm-10  text-left">
        {!! Form::text('file_prefix', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Generate Code Field -->
<div class="form-group row">
    @if (@$code->is_ready == 0)
    <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg">Generate Code Data?</label>
    <div class="col-sm-10">
        {!! Form::checkbox('is_ready', '1', null) !!}
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
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-primary " id="submit">Save</button>
        <a href="{!! route('codes.index') !!}" class="btn btn-default">Cancel</a>
    </div>
</div>


@section('scripts')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>
let dstr = '';

$(document).on('change', '[name="x1"]', function() {
    calDiff();
});

$(document).on('change', '[name="x2"]', function() {
    calDiff();
});

$(document).on('change', '[name="n1"]', function() {
    calDiff();
});

$(document).on('change', '[name="n2"]', function() {
    calDiff();
});

$(document).on('change', '[name="n3"]', function() {
    calDiff();
});

$(document).on('change', '[name="x3"]', function() {
    calDiff();
});

$(document).on('change', '[name="n4"]', function() {
    calDiff();
});

$(document).on('change', '[name="n5"]', function() {
    calDiff();
});

$(document).on('change', '[name="n6"]', function() {
    calDiff();
});

$(document).on('change', '[name="x4"]', function() {
    calDiff();
});

$(document).on('change', '[name="file_prefix"]', function() {
    let file_pre = $("[name=file_prefix]").val();
    $("#calculatordiff").html(dstr + ' Files start with ' + file_pre + '*.txt');
});

function calDiff() {
    let x1 = $("[name=x1]").val();
    let x2 = $("[name=x2]").val();
    let n1 = $("[name=n1]").val();
    let n2 = $("[name=n2]").val();
    let n3 = $("[name=n3]").val();
    let x3 = $("[name=x3]").val();
    let n4 = $("[name=n4]").val();
    let n5 = $("[name=n5]").val();
    let n6 = $("[name=n6]").val();
    let x4 = $("[name=x4]").val();
    let file_prefix = $("[name=file_prefix]").val();
    
    if (x1.length > 0 && x2.length > 0 && 
        n1.length > 0 && n2.length > 0 && 
        n3.length > 0 && x3.length > 0 && 
        n4.length > 0 && n5.length > 0 && 
        n6.length > 0 && x4.length > 0
       )
    {
        let nf = new Intl.NumberFormat('en-US');
        
        $.ajax({
            type: 'GET',
            url: '/code-caldiff',
            data: {x1:x1, x2:x2, n1:n1, n2:n2, n3:n3, x3:x3, n4:n4, n5:n5, n6:n6, x4:x4},
            success:function(response) {
                dstr = 'The total number of differences is: ' + nf.format(response.data) + ' codes.';
                if (file_prefix.length > 0) {
                    dstr = dstr + ' Files start with ' + file_prefix + '*.txt';
                }
                $("#calculatordiff").html(dstr);
            }
        });
    }

}

</script>
@endsection
