

<div class="row mx-5 my-4">
    <div class="col-sm-5">
        <label for="name">Name
            @if (@$errors->any())
                <span class="text-danter"> {{ @$errors->first('name') }}</span>
            @endif

        </label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? @$paymentMethod->name ?? '' }}" placeholder="Name">
    </div>
    <div class="col-sm-5">
        <label for="slug">Slug

            @if (@$errors->any())
            <span class="text-danter"> {{ @$errors->first('slug') }}</span>
            @endif
        </label>
        <input type="text" class="form-control" id="slug" value="{{ old('slug') ?? @$paymentMethod->slug ?? '' }}" name="slug" placeholder="Slug">
    </div>
</div>

<div class="row mx-5 my-4">
    <div class="col-sm-10">
        <label for="name">Flag</label>
        {!! Form::select('flag', $paymentMethodFlag , old('flag') ?? @$paymentMethod->flag ?? 0, ['class' => 'form-control select2', 'placeholder' => 'Please select provider', 'required']) !!}
    </div>
</div>

<div class="row mx-5 my-2 mt-4">
        <div class="form-group col-sm-10 d-flex justify-content-end">
            <a href="{{ route(@$routeName .'.index') }}" class="btn btn-light mr-3">Cancel</a>
            <Button class="btn btn-primary">Save</Button>
        </div>
</div>


<script>
    $(document).on('keyup', '[name=name]', function(event) {
        let name = $(this).val();
        $('#slug').val(name.toLowerCase())
    });

</script>


