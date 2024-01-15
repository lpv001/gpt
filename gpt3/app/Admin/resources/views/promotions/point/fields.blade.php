<div class="col-sm-12">
    @include('adminlte-templates::common.errors')
</div>

<div class="form-group col-sm-12">
  <div class="col-sm-4 p-0">
    <label for="exampleInputPassword1">Description</label>
    <input type="text" class="form-control" name="title" value="{{ old('title') ?? @$point ? @$point->title : '' }}" placeholder="description" required>
  </div>
</div>

<div class="form-group col-sm-12">
    <div class="col-sm-4 p-0">
        <label for="user">User</label>
        <div class="d-flex flex-row">
          {!! Form::select('user_id', @$user, @$point ? @$point->user_id : [], ['placeholder' => 'Select user', 'class' => 'form-control select2', 'required']) !!}
          {{-- <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-plus"></i></a> --}}
        </div>
    </div>
</div>

<div class="form-group col-sm-12">
  <div class="col-sm-4 p-0">
    <label for="exampleInputPassword1">Total Points</label>
    <input type="text" class="form-control number" name="total" value="{{ old('total') ?? @$point ? @$point->total : 0 }}" placeholder="Total Points" autocomplete="off" required>
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
