<div class="container mt-3">

@if (env('APP_IS_MARKETPLACE'))
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Shop</label>
        <div class="col-sm-10">
            {!! Form::select('shop_id', $shops, @$edit? $product->shop_id  : 1, ['placeholder' => 'Please select Shop', 'class' => 'form-control select2', 'required']) !!}
        </div>
    </div>
@endif
@if (!env('APP_IS_MARKETPLACE'))
    {{ Form::hidden('shop_id', 1) }}
@endif

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Category</label>
        <div class="col-sm-10">
            {!! Form::select('category_ids[]', $categories, @$edit? $product->category_ids : [], ['placeholder' => 'Please select Category', 'class' => 'form-control select2', 'required', 'multiple' => 'multiple']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Brand</label>
        <div class="col-sm-10">
            {!! Form::select('brand_id', $brands, @$edit? $product->brand_id : [], ['placeholder' => 'Please select brand','class' => 'form-control select2']) !!}
        </div>
    </div>
    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Unit</label>
        <div class="col-sm-10">
            {{-- {!! Form::select('brand_id', $brands, @$edit? $product->brand_id : [], ['placeholder' => 'Please select brand','class' => 'form-control select2', 'required']) !!} --}}
            {!! Form::select('unit', $units, @$edit ? $product->unit_id : [], ['placeholder' => 'Pick Unit','class' => 'form-control select2', 'id' => 'unit']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Code</label>
        <div class="col-sm-10">
            {!! Form::text('product_code', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg"   class="col-sm-2  col-form-label col-form-label-lg text-right">Name</label>
        <div class="col-sm-10">
            @include('admin::products.components.product_name')
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Price</label>
        <div class="col-sm-10">
            <input type="text" class="form-control number" id="pprice" name="price" value="{{ @$edit ? $product->unit_price : 0.00 }}" placeholder="$ 0.00">
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Images</label>
        <div class="col-sm-10">
              <h5><strong>Crop images to fit the layout? </strong> 
               <span class="px-2">
                   <input type="checkbox" name="enableCropImage" id="enableCropImage" {{ $is_cropped_image ? 'checked' : '' }} />
               </span>
              </h5>
            <div class="input-images"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Product Option</label>
        <div class="col-sm-10">
           <h5><strong>Does this product come in multiple options like size or color ? </strong> 
               <span class="px-2">
                   <input type="checkbox" id="enableOption" {{ (@$product_options && count(@$product_options) > 0) ? 'checked' : ''}} />
               </span>
            </h5>

            @include('admin::products.components.product_option')
        </div>
    </div>

    

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Active</label>
        <div class="col-sm-10">
            <label class="checkbox-inline">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', '1', null) !!}
            </label>
        </div>
    </div>

    <div class="form-group row">
        <label for="colFormLabelLg" class="col-sm-2  col-form-label col-form-label-lg text-right">Is Promoted</label>
        <div class="col-sm-10">
            <label class="checkbox-inline">
                {!! Form::hidden('is_active', 1) !!}
                {!! Form::checkbox('is_promoted', '1', null) !!}
            </label>
        </div>
    </div>
    <!-- Submit Field -->
    <div class="for-group row">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-10">
            {{-- {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!} --}}
            <button type="submit" class="btn btn-primary " id="submit">Save</button>
            <a href="{!! route('products.index') !!}" class="btn btn-default">Cancel</a>

        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).on('keypress', '.select2-search__field', function(event) {
        let select = $(this).closest('tr').find('.option-value');
        let value = $(this).val().replace(',','');
        
        if (event.which == 44) {
            if ($(select).find("option[value='" + value + "']").length || value == '') {
                console.log($(select).find("option[value='" + $(this).val() + "']").length)
            } else { 
                // Create a DOM Option and pre-select by default
                var newOption = new Option(value, value, true, true);
                // Append it to the select
                $(select).append(newOption).trigger('change');
            } 
            $(this).val('');

        }
    });

	function number() {
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
		$(".number").inputFilter(function(value) {
  			return /^-?\d*[.,]?\d*$/.test(value);
		});
	}

    $(document).delegate('.more-option', 'click', function(event){
        event.preventDefault();
        let length_row = parseInt($('#product-options > tr').length) + 1;

        $('#product-options').append(`
            <tr>
                <td>
                    <span class="mt-4">${length_row}</span>
                </td>
                <td>
                    <input type="text" class="form-control options" name="options[]" required>
                </td>
                <td class="select-row">
                    {!! Form::select('option_values_${length_row}[]', [], [], ['class' => 'form-control select2 option-value', 'required', 'multiple' => 'multiple']) !!}
                </td>
                <td>
                    <a href="#" class="btn btn-sm btn-primary btn-create-option-image">Option Image</a>
                </td>
                <td><a class="btn btn-sm btn-light btn-remove"><i class="fa fa-minus"></i></a></td>
            </tr>
        `);
        $('select').select2();      
    });

        $(document).delegate('.btn-remove', 'click', function(){
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.flag-product', function() {
            $('.flag').val(0);
            $(this).closest('tr').find('.flag').val(1)
        });

        // product with description
        $(document).on('click', '.add-more-product', function() {
            let row = $('table tbody tr').length;
            if (row >= 3 )
                return;
            // console.log(row);

            $('#product_translation').append(`
                <tr>
                    <td class="py-4">1</td>
                    <td>
                        {!! Form::select('lang[]', ['km' => 'Khmer'], 'km', ['placeholder' => 'Select Langauge','class' => 'form-control select2', 'required']) !!}
                    </td>
                    <td>
                        <input class="form-control" type="text" value="" name="name[]" required>
                    </td>
                    <td>
                        <textarea class="form-control" name="description[]" cols="30" rows="1" required></textarea>
                    </td>
                    <td><a class="btn btn-sm btn-danger btn-remove"><i class="fa fa-minus"></i></a></td>
                </tr>
            `);
            $('select').select2();
        });

        $(document).on('click', '#enableOption', function(event) {
           if ($(this).prop("checked") == true) {
               $('#dProductOption').css({display:'block'});
               $('select').select2();

               $('.option-validation').each(function(key, element) {
                    $(element).attr('required', true);
               });
           } else {
                $('#dProductOption').css({display:'none'});
                $('.option-validation').each(function(key, element) {
                    $(element).attr('required', false);
               });
           }
        });

        $(document).on('blur', '.options', function(event) {
            event.preventDefault();
            if ($(this).val() == '') $('#varientOption').empty();
        });

        //
        $(document).on('click', '#createVarient', function(event) {
            event.preventDefault();

            $('#variant').css({display:'block'});
            $('#varientOption').empty();

            let index = 0;
            let first_option = $('.option-value')[index];
            first_options = $(first_option).children('option:selected');

            let selectElement = $("select.option-value:not(:first)");

            let arr = new Array;
            $(first_options).each(function(firstKey, firstValue) {
                let optionArray = new Array;
                let object = {};
                object['index'] = $(firstValue).text();
                object.data = [];

                $(selectElement).each(function(key, ele) {
                    let options = $(ele).children('option:selected');
                    $(options).each(function(keyOption, optionEle) {
                        optionArray.push($(optionEle).text());
                    });

                    object.data = optionArray;
                });

                arr.push(object);
            });
            // console.log(arr)
            createVarientTable(arr);
        });

        function createVarientTable (arrayData) {
            $('#varientOption').empty();

            arrayData.forEach((element, index) => {
                object = {};
                index = element.index;
                
                if (element.data.length <= 0) {
                    object.name = `${index}`;
                    object.data = [index];
                    table(object)
                    return;
                }

                (element.data).forEach(element => {
                    object.data = `${index},${element}`;
                    object.name = `${index} / ${element}`;
                    table(object)
                });                
            });
        }

        function table (objectData) {
            let price = $('#pprice').val();
            let code = $('[name="product_code"]').val();
            $('#varientOption').append(`
                <tr>
                    <td>
                        ${objectData.name} 
                        <input type="hidden" value="${object.data}" name="varient[]">      
                    </td>
                    <td>
                        <input type="text" value="${price != '' ? price : 0.00 }" name="varient_price[]" class="form-control number" placeholder="0.00">
                    </td>
                   
                    <td><a class="btn btn-sm btn-danger btn-remove"><i class="fa fa-minus"></i></a></td>
                </tr>
            `);
        }
        
        $(document).on('click', '.btn-create-option-image', function(event) {
            event.preventDefault();
            let option = $(this).closest('tr').find('.options').val();
            let option_value = $(this).closest('tr').find('.option-value').children('option:selected');
            if (option == '' && option_value.length <= 0) 
                return;

            let op_img_str = 'option_value_images';
            if($('#' + option).length > 0) {
              op_img_str = 'add_option_value_images';
            }
            
            let tr = '';
            $(option_value).each(function(key, element) {
              let option_value_str = $(element).val();
              if($('#' + option + '-' + option_value_str).length > 0){
                tr += ``;
              } else {
                tr += `
                    <tr id="` + option + `-` + option_value_str + `">
                        <td>
                            ${$(element).val()}
                        </td>
                        <td>
                            <div class="d-flex">
                                <div class="mr-2">
                                    <img src="" alt="" style="width: 70px; height:70px;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" accept="image/*" class="custom-file-input" name="option_images[` + key + `]" required>
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                              <input type="hidden" value="${$(element).val()}" name="` + op_img_str + `[` + key + `]">
                        </td>
                    </tr>
                `;
              }
            });

            tableOptionImage(option, tr);
        });

        function tableOptionImage(title,ele) {
          if($('#' + title).length > 0){
            $('#tbody-'+title).append(`
            ${ele}
            `);
          } else {
            $('#table-responsive').append(`
                <table class="table" id="` + title + `" data-id="` + title + `">
                        <thead>
                            <tr>
                                <td width="15%">N<sup>o</sup></td>
                                <td>${title}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-danger pull-right table-option-image">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </td>
                            </tr>
                        </thead>
                    <tbody id="tbody-` + title + `">
                        ${ele}
                    </tbody>
                </table>
            `);
          }
        }

        $(document).on('click', '.btn-delete-option-image', function(event) {
            event.preventDefault();
            let image_id = $(this).data('id');
            
            $(this).closest('tbody').append(`
            <input type="hidden" value="` + image_id + `" name="option_value_delete_image_id[]">
            `);
            
            
            $(this).closest('tr').remove();
        });

        $(document).on('change', '.custom-file-input', function(event) {
            event.preventDefault();
            let image = $(this).closest('tr').find('img');
            let image_id = $(this).data('id');

            $(this).closest('tr').find('[name="option_value_delete_image_id[]"]').val(image_id);
            console.log(image_id);

            if ($(this)[0].files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    image.attr('src', e.target.result);
                }
                reader.readAsDataURL($(this)[0].files[0]);
            }
        });
</script>
@endsection


