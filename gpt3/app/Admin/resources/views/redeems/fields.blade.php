{{-- <div class="col-sm-12">
    @include('adminlte-templates::common.errors')
</div> --}}

<div class="row">
  {{--  --}}
  <div class="col-sm">

    <div class="form-group col-sm-12">
      <div class="col-sm p-0">
        <label for="exampleInputPassword1">English Name</label>
        <input type="text" class="form-control" name="name[]" value="{{ old('name') ?? @$promotion ? @$promotion->name_en : '' }}" placeholder="Enter Name" required>
        <input type="hidden" name="locale[]" value="en" id="">
      </div>
    </div>
    
    <div class="form-group col-sm-12">
      <div class="col-sm p-0">
        <label for="exampleInputPassword1">Khmer Name</label>
        <input type="text" class="form-control" name="name[]" value="{{ old('name') ?? @$promotion ? @$promotion->name_km : '' }}" placeholder="Enter Name" required>
        <input type="hidden" name="locale[]" value="km" id="">
      </div>
    </div>
    
    <div class="form-group col-sm-12">
        <div class="col-sm p-0">
            <label for="exampleInputEmail1">Promotion Type</label>
            <div class="d-flex flex-row">
              {!! Form::select('promotion_type_id', @$promotion_type, @$promotion ? @$promotion->promotion_type_id : 0, ['class' => 'form-control select2', 'style' => 'width:100%']) !!}
              {{-- <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-plus"></i></a> --}}
            </div>
        </div>
      </div>

    
      <div class="form-group col-sm-12">
        <div class="col-sm p-0">
          <div class="col-sm p-0 d-flex">
            <label class="mr-auto p-2">Points <span id="discountType"></span></label>
            <div class="p-2">
    
            </div>
          </div>
          <input type="text" class="form-control number" name="value" value="{{ old('value') ?? @$promotion ? @$promotion->value : '' }}" placeholder="0.00" autocomplete="off" required>
          <input type="hidden" name="discount_flag" value="{{ @$promotion ? @$promotion->flag : 1 }}">
        </div>
    </div>
    
    <div class="form-group col-sm-12">
      <div class="col-sm p-0">
        <label for="exampleInputPassword1">Quantity</label>
        <input type="text" class="form-control number" name="qty" value="{{ old('qty') ?? @$promotion ? @$promotion->qty : 1 }}" placeholder="Quantity" autocomplete="off" required>
      </div>
    </div>

    <div class="col-sm-12">
      <div class="col-sm p-0">
        <label for="exampleInputPassword1">Image</label>
        <div class="input-images"></div>
      </div>
    </div>

    <div class="col-sm-12 mt-3">
      <div class="col-sm p-0">
        <button class="btn btn-sm btn-primary pull-right">Save</button>
        <a href="{{ route('redeem.index') }}" class="btn btn-sm btn-secondary pull-right mr-2">Cancel</a>
      </div>
    </div>

  </div>

  <div class="col-sm"></div>
</div>


@section('scripts')
    <script>
      $(document).on('click', '.generate-code', function() {
        $.get('{{ route('promotion.generate-code') }}', function(response) {
            if (response.status)  {
              $('[name="code"]').val(response.code);
            }
          
        });
      });
    </script>
@endsection
