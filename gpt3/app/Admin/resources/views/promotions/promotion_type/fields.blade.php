<div class="col-sm-12">
    @include('adminlte-templates::common.errors')
</div>
  <div class="form-group col-sm-12">
    <div class="col-sm-4 p-0">
        <label for="exampleInputEmail1">Name In English</label>
        <input type="text" class="form-control" name="name[]" value="{{ @$promotion_type->name_en ?? '' }}"  placeholder="Enter Name">
        <input type="hidden" name="locale[]" value="en">
    </div>
  </div>

  <div class="form-group col-sm-12">
      <div class="col-sm-4 p-0">
        <label for="exampleInputPassword1">Name In Khmer</label>
        <input type="text" class="form-control" name="name[]" value="{{ @$promotion_type->name_km ?? '' }}" placeholder="Enter Name">
        <input type="hidden" name="locale[]" value="km">
      </div>
  </div>

  <div class="form-group col-sm-12">
    <div class="col-sm-4 p-0">
      <button class="btn btn-sm btn-primary pull-right">Save</button>
    </div>
</div>
