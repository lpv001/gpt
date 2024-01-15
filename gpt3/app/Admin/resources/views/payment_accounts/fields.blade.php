
<div class="row mx-5 my-4">
    <div class="col-sm-5">
        <label for="accountName">Account Name 
            @if (@$errors->any())
                <span class="text-danger"> {{ @$errors->first('account_name') }}</span>
            @endif

        </label>
        <input type="text" class="form-control" id="accountName" name="account_name" value="{{ old('account_name') ?? @$payment_account->account_name ?? '' }}" placeholder="Account Name">
    </div>
    <div class="col-sm-5">
        <label for="accountNumber">Account Number

            @if (@$errors->any())
            <span class="text-danger"> {{ @$errors->first('account_number') }}</span>
            @endif
        </label>
        <input type="text" class="form-control" id="accountNumber" value="{{ old('account_number') ?? @$payment_account->account_number ?? '' }}" name="account_number" placeholder="Account Number">
    </div>
</div>


<div class="row mx-5 my-4">
    <div class="col-sm-5">
        <label for="accountName">Payment Provider <span class="ml-2 text-danger">*</span></label>
        {!! Form::select('provider_id', $paymentProviders , old('provider_id') ?? @$payment_account->provider_id ?? '', ['class' => 'form-control select2', 'placeholder' => 'Please select provider', 'required']) !!}
    </div>

    <div class="col-sm-5">
        <label for="phone_number">Phone Number

            @if (@$errors->any())
            <span class="text-danger"> {{ @$errors->first('phone_number') }}</span>
            @endif
        </label>
        <input type="text" class="form-control" id="phone_number" value="{{ old('phone_number') ?? @$payment_account->phone_number ?? '' }}" name="phone_number" placeholder="Phone Number">
    </div>
    {{-- <div class="col-sm-5">
        <label for="accountNumber">Payment Account Type <span class="ml-2 text-danger">*</span></label>
        {!! Form::select('payment_account_type_id', $paymentAccountTypes , old('payment_account_type_id') ?? @$payment_account->payment_account_type_id ?? '', ['class' => 'form-control select2', 'placeholder' => 'Please select account type', 'required']) !!}
    </div> --}}
</div>


<div class="row mx-5 my-4">
    <div class="col-sm-5">
        <label for="qrCode">Payment Method</label>
        {!! Form::select('method_id', $paymentMethods , old('method_id') ?? @$payment_account->method_id ?? '', ['class' => 'form-control select2', 'placeholder' => 'Please select Method', 'required']) !!}
    </div>
    <div class="col-sm-5">
        <label for="qrCode">Upload QR Code</label>
        <input type="file" class="form-control" name="qr_code" id="qrCode" accept="image/*">
    </div>
</div>


<div class="row mx-5 my-2 mt-5">
    <div class="col-sm-10">
        <div class="bg-light h5 px-4 py-3">
            <div class="d-flex justify-content-between">
                <h5>Display Fields <span class="text-danger">*</span></h5>
                <a href="#" class="btn btn-primary btn-add mb-4">Add New</a>
            </div>


            <table class="table" id="displayField">
                <thead>
                    <th width="40%">Field Name</th>
                    <th width="30%">Data Type</th>
                    <th width="30%">Note</th>
                    <th width=""></th>
                </thead>
                <tbody>

                    @if (count(@$display_fields) > 0)
                        @foreach ($display_fields as $field)
                            <tr>
                                <td><input type="text" class="form-control" value="{{ $field['field_name'] }}" name="field_names[]" placeholder="Enter Field Name" required></td>
                                <td>
                                    {!! Form::select('field_types[]', $dataTypes , @$field['field_data_type'] ?? '', 
                                    [
                                        'class' => 'form-control select2 w-100', 
                                        'placeholder' => 'Please select data type', 
                                        'required',
                                        'style' => 'width:100% !important'
                                    ]) !!}
                                </td>
                                <td><input type="text" class="form-control" value="{{ $field['note'] }}" name="notes[]" placeholder="Enter Field Name"></td>
                                <td>
                                    {{-- <a href="#" class="btn btn-danger btn-delete"><i class="fa fa-trash" aria-hidden="true"></i></a> --}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td><input type="text" class="form-control" value="" name="field_names[]" placeholder="Enter Field Name" required></td>
                            <td>
                                {!! Form::select('field_types[]', $dataTypes ,[], 
                                [
                                    'class' => 'form-control select2 w-100', 
                                    'placeholder' => 'Please select data type', 
                                    'required',
                                    'style' => 'width:100% !important'
                                ]) !!}
                            </td>
                            <td><input type="text" class="form-control" value="" name="notes[]" placeholder="Enter Field Name"></td>
                            <td>
                                {{-- <a href="#" class="btn btn-danger btn-delete"><i class="fa fa-trash" aria-hidden="true"></i></a> --}}
                            </td>
                        </tr>
                    @endif

                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mx-5 my-2 mt-4">
        <div class="form-group col-sm-10 d-flex justify-content-end">
            <a href="{{ route(@$routeName .'.index') }}" class="btn btn-light mr-3">Cancel</a>
            <Button class="btn btn-primary">Save</Button>
        </div>
</div>


<script>
    $(document).on('click', '.btn-add', function(event) {
        event.preventDefault();

        $('#displayField > tbody').append(`
            <tr>
                <td><input type="text" class="form-control" value="" name="field_names[]" placeholder="Enter Field Name" required></td>
                <td>
                    {!! Form::select('field_types[]', $dataTypes ,[], 
                    [
                        'class' => 'form-control select2 w-100', 
                        'placeholder' => 'Please select data type', 
                        'required',
                        'style' => 'width:100% !important'
                    ]) !!}
                </td>
                <td><input type="text" class="form-control" value="" name="notes[]" placeholder="Note"></td>
                <td>
                    <a href="#" class="btn btn-danger btn-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                </td>
            </tr>
        `);
        $('select').select2();
    });

    $(document).delegate('.btn-delete','click', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });
</script>


