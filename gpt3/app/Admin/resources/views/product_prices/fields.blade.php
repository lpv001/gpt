<div class="container mt-3">
            <table class="table" style="width: 100% !important;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th width="20%">Product</th>
                        <th>Unit</th>
                        <th width=15%>City</th>
                        <th>Qty</th>
                        <th>Distributor</th>
                        <th>Wholesaler</th>
                        <th>Retailer</th>
                        <th>Buyer</th>
                        @if (!@$edit)
                            <th>
                                <a class="btn btn-sm btn-primary addMore"> <i class="fa fa-plus"></i> </a>
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="pt-4">1</td>
                        <td>
                            @if (@$edit)
                                {!! Form::select('product_id[]', $product, @$productPrice->product_id ?? [], ['placeholder' => 'Pick Product', 'class' => 'form-control select2 selected_product', 'required', 'style' =>'width:100% !important', 'disabled']) !!}
                                {!! Form::hidden('product_id[]', @$productPrice->product_id) !!}
                            @else   
                                {!! Form::select('product_id[]', $product, @$productPrice->product_id ?? [], ['placeholder' => 'Pick Product', 'class' => 'form-control select2 selected_product', 'required', 'style' =>'width:100% !important']) !!}
                            @endif
                        </td>
                        <td>
                            @if (@$edit)
                                {!! Form::select('unit_id[]', $unit, @$productPrice->unit_id ?? [], ['class' => 'form-control select2 unit', 'required',  'style' =>'width:100% !important', 'disabled']) !!}
                                {!! Form::hidden('unit_id[]', @$productPrice->unit_id) !!}
                            @else   
                                {!! Form::select('unit_id[]', $unit, [], ['placeholder' => 'Pick Unit', 'class' => 'form-control select2 unit', 'required',  'style' =>'width:100% !important']) !!}
                            @endif
                        </td>
                        <td>
                            @if (@$edit)
                                {!! Form::select('city_id[]', $city, @$productPrice->city_id ?? [], ['placeholder' => 'All', 'class' => 'form-control select2', 'style' =>'width:100% !important', 'disabled']) !!} 
                                {!! Form::hidden('city_id[]', @$productPrice->city_id) !!}
                            @else   
                                {!! Form::select('city_id[]', $city, @$productPrice->city_id ?? [], ['placeholder' => 'All', 'class' => 'form-control select2', 'style' =>'width:100% !important']) !!} 
                            @endif
                        </td>
                        <td>
                            {!! Form::text('qty_per_unit[]', @$productPrice->qty_per_unit ?? 1, ['class' => 'form-control number', 'required', @$edit ? 'readonly' : '']) !!}
                        </td>
                        <td>
                            {!! Form::text('distributor[]', @$productPrice->distributor ?? '', ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {!! Form::text('wholesaler[]', @$productPrice->wholesaler ?? '', ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {!! Form::text('retailer[]', @$productPrice->retailer ?? '', ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {!! Form::text('buyer[]', @$productPrice->buyer ?? '', ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {{-- {{dd(@$productPrice->flag)}} --}}
                            <input type="checkbox" class="" name="flag[]" value="{{@$productPrice->flag ? 1 : 0 }}"  {{@$productPrice->flag ? 'checked' : '' }}/>
                            {{-- {!! Form::checkbox('flag[]', @$productPrice->flag ?? 0, ['class' => 'form-control boolean', 'required']) !!} --}}
                        </td>
                        <td>
                            @if (!@$edit)
                                <a class="btn btn-sm btn-light"><i class="fa fa-minus"></i></a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>


    <!-- Submit Field -->
    {{-- <div class="for-group row"> --}}
        {{-- <div class="col-sm-2"></div> --}}
        <div class="form-group col-sm-10">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('products.index') !!}" class="btn btn-default">Cancel</a>
        </div>
    {{-- </div> --}}
</div>

<script>
    $(function() {
            $.fn.inputFilter = function(inputFilter) {
                return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
                });
            };
            // Validate input Unit Price field 
            $(":text").inputFilter(function(value) {
                return /^-?\d*[.,]?\d*$/.test(value); 
            });
            // Validate input Sale Price field 
            $("#saleprice").inputFilter(function(value) {
                return /^-?\d*[.,]?\d*$/.test(value); 
            });
        });
</script>