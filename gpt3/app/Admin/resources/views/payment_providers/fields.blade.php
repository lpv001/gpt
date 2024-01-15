
<div class="row">
    <div class="form-group col-sm-6 mx-4 my-2">
        <label for="exampleFormControlInput1">Provider name 
            <span class="text-danger">*</span>

            @if ($errors->any())
                <span class="text-danger"> {{ $errors->first('name') }} </span>
            @endif
        </label>
        <input type="text" class="form-control" id="name" name="name" placeholder="e.g ACELEDA Tonchet" value="{{ @$payment->name }}">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-6 mx-4 my-2">
        <label for="exampleFormControlInput1">Description 
            <span class="text-danger">*</span>
            @if ($errors->any())
                <span class="text-danger"> {{ $errors->first('description') }} </span>
            @endif
        </label>
        <textarea class="form-control" name="description" id="description" cols="3" rows="5">{{@$payment->description}}</textarea>
      </div>
</div>  


<div class="row">
    <div class="form-group col-sm-6 mx-4 my-2">
        <label for="exampleFormControlInput1">Icon 
            <span class="text-danger">*</span>
            @if ($errors->any())
                <span class="text-danger"> {{ $errors->first('images') }} </span>
            @endif
    </label>
        <div class="input-images"></div>
    </div>
</div>  


<div class="row">
    <div class="form-group col-sm-6 mx-4 my-2 d-flex justify-content-end">
        <a href="{{ route('payment-provider.index') }}" class="btn btn-light mr-3">Cancel</a>
        <Button class="btn btn-primary">Save</Button>
    </div>
</div>  