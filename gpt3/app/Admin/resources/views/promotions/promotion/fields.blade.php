<div class="col-sm-12">
    @include('adminlte-templates::common.errors')
</div>

<div class="form-group col-sm-12">
  <div class="col-sm-4 p-0">
    <label for="exampleInputPassword1">English Name</label>
    <input type="text" class="form-control" name="name[]" value="{{ old('name') ?? @$promotion ? @$promotion->name_en : '' }}" placeholder="Enter Name" required>
    <input type="hidden" name="locale[]" value="en" id="">
  </div>
</div>

<div class="form-group col-sm-12">
  <div class="col-sm-4 p-0">
    <label for="exampleInputPassword1">Khmer Name</label>
    <input type="text" class="form-control" name="name[]" value="{{ old('name') ?? @$promotion ? @$promotion->name_km : '' }}" placeholder="Enter Name" required>
    <input type="hidden" name="locale[]" value="km" id="">
  </div>
</div>

<div class="form-group col-sm-12">
    <div class="col-sm-4 p-0">
        <label for="exampleInputEmail1">Promotion Type</label>
        <div class="d-flex flex-row">
          {!! Form::select('promotion_type_id', @$promotion_type, @$promotion ? @$promotion->promotion_type_id : [], ['placeholder' => 'Select Promotion Type', 'class' => 'form-control select2', 'required']) !!}
          {{-- <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-plus"></i></a> --}}
        </div>
    </div>
  </div>

  <div class="form-group col-sm-12">
      <div class="col-sm-4 p-0">
        <label for="exampleInputPassword1">Code</label>
        <div class="d-flex flex-row">
          <input type="text" class="form-control" name="code" value="{{ old('code') ?? @$promotion ? @$promotion->code : ''}} " placeholder="Enter Code" required>
          <a href="#" class="btn btn-sm btn-secondary generate-code">Generate</a>
        </div>
      </div>
  </div>

  <div class="form-group col-sm-12">
    <div class="col-sm-4 p-0">
      <div class="col-sm p-0 d-flex">
        <label class="mr-auto p-2">Discount <span id="discountType"></span></label>
        <div class="p-2">

          <div class="form-check">
            <input class="form-check-input value-type" type="radio" name="valueType" data-text="(%)" id="exampleRadios1" value="1" {{ @$promotion ?( @$promotion->flag == 1 ? 'checked' : '') : ''  }} >
            <label class="form-check-label ml-4 pl-2">
              (%)
            </label>
          </div>

        </div>
        <div class="p-2">

          <div class="form-check">
            <input class="form-check-input value-type" type="radio" name="valueType" data-text="($)" id="exampleRadios1" value="2" {{ @$promotion ?( @$promotion->flag == 2 ? 'checked' : '') : ''  }}>
            <label class="form-check-label ml-4 pl-2">
              ($)
            </label>
          </div>

        </div>
      </div>
      <input type="text" class="form-control number" name="value" value="{{ old('value') ?? @$promotion ? @$promotion->value : '' }}" placeholder="0.00" autocomplete="off" required>
      <input type="hidden" name="discount_flag" value="{{ @$promotion ? @$promotion->flag : 1 }}">
    </div>
</div>

<div class="form-group col-sm-12">
  <div class="col-sm-4 p-0">
    <label for="exampleInputPassword1">Quantity</label>
    <input type="text" class="form-control number" name="qty" value="{{ old('qty') ?? @$promotion ? @$promotion->qty : 1 }}" placeholder="Quantity" autocomplete="off" required>
  </div>
</div>

<div class="form-group col-sm-12">
  <div class="col-sm-4 p-0">
    <label for="exampleInputPassword1">Start Date</label>
    <input type="date" class="form-control" name="start_date" value="{{ old('start_date') ?? @$promotion ? @$promotion->start_date : '' }}" placeholder="Quantity" autocomplete="off" required>
  </div>
</div>

<div class="form-group col-sm-12">
  <div class="col-sm-4 p-0">
    <label for="exampleInputPassword1">End Date</label>
    <input type="date" class="form-control" name="end_date" value="{{ old('end_date') ?? @$promotion ? @$promotion->end_date : '' }}" placeholder="Quantity" autocomplete="off" required>
  </div>
</div>

  <div class="form-group col-sm-12">
    <div class="col-sm-4 p-0">
      <button class="btn btn-sm btn-primary pull-right">Save</button>
    </div>
</div>

<!-- Button trigger modal -->
{{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button> --}}

<!-- Modal -->
{{-- <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLongTitle">Create Promotion Type</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="form-group col-sm-12">
          <div class="col-sm-12 p-0">
              <label for="exampleInputEmail1">Name In English</label>
              <input type="text" class="form-control" name="name[]" value=""  placeholder="Enter Name">
              <input type="hidden" name="locale[]" value="en">
          </div>
        </div>
      
        <div class="form-group col-sm-12">
            <div class="col-sm-12 p-0">
              <label for="exampleInputPassword1">Name In Khmer</label>
              <input type="text" class="form-control" name="name[]" value="" placeholder="Enter Name">
              <input type="hidden" name="locale[]" value="km">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary new-promotion-type">Save</button>
      </div>
    </div>
  </div>
</div> --}}

@section('scripts')
    <script>
      $(document).ready(function() {
        $('.value-type').each(function(keyElement, element) {
          let is_checked = $(element).attr("checked") == "checked";
            if (is_checked) {
              $('#discountType').text($(element).data('text'));
            }
        });
      });

      $(document).on('change', '.value-type', function() {
        $('#discountType').text($(this).data('text'));
        $('[name="discount_flag"]').val($(this).val());
      })

      $(document).on('click', '.generate-code', function() {
        $.get('{{ route('promotion.generate-code') }}', function(response) {
            if (response.status)  {
              $('[name="code"]').val(response.code);
            }
          
        });
      });
    </script>
@endsection
