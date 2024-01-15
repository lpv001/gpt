<div class="row mx-2 my-2">
    <div class="col-sm bg-warning">
        @if($errors->any())
            <p class="text-white">{{$errors}}</p>
        @endif
    </div>
</div>  

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('frontend.product') }}</a>
        <a class="nav-item nav-link " id="nav-profile-tab" data-toggle="tab"  href="#nav-select" role="tab" aria-controls="nav-profile" aria-selected="false" disabled="disabled">{{__('frontend.brand_and_category')}}</a>
        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">{{__('frontend.prices')}}</a>
        </div>
    </nav>

    {{-- Start set product --}}
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active bg-active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="form-group row mt-2">
            <div class="col-sm-12">
                <p class="mb-1">{{ __('frontend.code') }}</p>
                {!! Form::text('product_code', @$edit? @$product->product_code : '', ['class' => 'form-control', 'placeholder' => __('frontend.code')]) !!}
            </div>
        </div>

            @if (@$edit)
                @foreach ($product_translation as $key => $item)
                <div class="form-group product-name">
                    <div class="row">
                        <div class="col-sm d-flex">
                            <p class="mb-1">{{ __('frontend.name') }}</p>
                            @if (count($product_translation) <= 1)
                                <a id="btn-add-name" class="btn btn-sm ml-auto add-name"><i class="fa fa-icon fa-plus-circle" aria-hidden="true"></i></a>
                            @endif
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-sm">
                            <input type="text" name="name[]" value="{{ $item->name }}" placeholder="{{__('frontend.name_en')}}" class="form-control">
                            <input type="hidden" name="locale[]" value="{{$item->locale}}">
                            <textarea class="form-control mt-3" id="" name="description[]" cols="30" rows="3" placeholder="{{ __('frontend.descritpion_en') }}">{{ $item->description }}</textarea>
                        </div>
                    </div>
                </div>

                @endforeach
            @else
            <div class="form-group product-name">
                <div class="row">
                    <div class="col-sm d-flex">
                        <p class="mb-1">{{ __('frontend.name') }}</p>
                        <a id="btn-add-name" class="btn btn-sm ml-auto add-name"><i class="fa fa-icon fa-plus-circle" aria-hidden="true"></i></a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <input type="text" name="name[]" placeholder="{{__('frontend.name_en')}}" class="form-control">
                        <input type="hidden" name="locale[]" value="en">
                        {{-- {!! Form::text('name[]', @$edit? '' : old('name_en') ?? '', ['class' => 'form-control my-auto', 'placeholder' => __('frontend.name_en')]) !!} --}}
                        <textarea class="form-control mt-3" id="" name="description[]" cols="30" rows="3" placeholder="{{ __('frontend.descritpion_en') }}">{{ @$edit ? '' : '' }}</textarea>
                    </div>
                </div>
            </div>

            @endif


        <div class="form-group row">
            <div class="col-sm-12">
                <p class="mb-1">{{ __('frontend.image')}}</p>
                <div class="input-images"></div>
            </div>
        </div>

        <div class="for-group row">
            <div class="form-group col-sm-12 text-right">
                <a  class="btn btn-warning next-1">{{ __('frontend.next')}}</a>
            </div>
        </div>
    </div>
    {{-- End section tab of product --}}

    {{-- Brand & Category --}}
    <div class="tab-pane fade bg-active" id="nav-select" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="form-group row mt-2">
            <div class="col-sm-12">
            <p class="mb-1">{{ __('frontend.category') }} :</p>
                {!! Form::select('category_id', @$categories, @$edit? $product->category_id : [], ['placeholder' => __('frontend.select') . __('frontend.category'), 'class' => 'form-control select2', 'required']) !!}
            </div>
        </div>
        
        <div class="form-group row">
            <div class="col-sm-12">
                <p class="mb-1">{{ __('frontend.brands') }} :</p>
                {!! Form::select('brand_id', @$brands, @$edit? $product->brand_id : [], ['placeholder' => __('frontend.select') . __('frontend.brands'),'class' => 'form-control select2', 'required']) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <p class="mb-1">{{ __('frontend.point_rate') }} :</p>
                <input type="text" name="point_rate" value="{{@$edit ? $product->point_rate : 0.00}}" class="form-control" placeholder="0.00">
            </div>
        </div>

        <div class="for-group row">
            <div class="form-group col-sm-12 text-right">
                <a  class="btn btn-warning next-2">{{ __('frontend.next')}}</a>
            </div>
        </div>
    </div>
    {{-- End section tab of Brand & Category --}}


    {{-- Price --}}
    <div class="tab-pane fade bg-active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

            @if (@$edit)
                @foreach ($product_price as $key => $item)

                    <div class="form-group product-price mt-2">
                        <div class="row">
                            <div class="col-sm d-flex">
                                <p class="mb-1">{{ __('frontend.unit_price') }} {{ ++$key}}
                                </p>
                                @if (count($product_price) <= 1)
                                    <a id="btn-add-price" class="btn btn-sm ml-auto add-price"><i class="fa fa-icon fa-plus-circle" aria-hidden="true"></i></a>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm">
                                {!! Form::select('unit_id[]', @$units, @$item->unit_id ?? [], ['placeholder' => __('frontend.select') . ' ' . __('frontend.unit'),'class' => 'form-control', 'required']) !!}
                                <input type="hidden" name="qty[]" value="1">
                                {!! Form::select('city_id[]', [0 => __('frontend.all_city')] + @$cities, @$item->city_id ?? [], ['placeholder' => __('frontend.select') . ' ' . __('frontend.city_or_province'),'class' => 'form-control mt-3', 'required']) !!}
                                <div class="row">
                                    <div class="col-sm">
                                        <input type="text" name="distributor[]" value="{{ number_format(@$item->distributor,2) }}" class="form-control price mt-3" placeholder="{{ __('frontend.distributor_price') }}">
                                    </div>
                                    <div class="col-sm">
                                        <input type="text" name="wholesaler[]" value="{{ number_format(@$item->wholesaler, 2) }}" class="form-control price mt-3" placeholder="{{ __('frontend.wholesaler_price') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm">
                                        <input type="text" name="retailer[]" value="{{ number_format(@$item->retailer, 2) }}" class="form-control price mt-3" placeholder="{{ __('frontend.retailer_price') }}">
                                    </div>
                                    <div class="col-sm">
                                        <input type="text" name="buyer[]" value="{{ number_format(@$item->buyer, 2) }}" class="form-control price mt-3" placeholder="{{ __('frontend.buyer_price') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm flex-row">
                                        <p class="mb-1 mt-2">{{__('frontend.show')}} 
                                            <span class="text-primary">({{__('frontend.note_set_price')}})</span> 
                                            <span class="pl-2">
                                                <input type="radio" class="mt-3 price_flage" name="is_flag" {{@$item->flag == 1 ? 'checked': ''}}>
                                                <input type="hidden" value="{{@$item->flag == 1 ? 1: 0}}" name="price_flag[]">
                                            </span>
                                        </p>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                @endforeach
            @else
                <div class="form-group product-price mt-2">
                    <div class="row">
                        <div class="col-sm d-flex">
                            <p class="mb-1">{{ __('frontend.unit_price') }} 1 
                            </p>
                            <a id="btn-add-price" class="btn btn-sm ml-auto add-price"><i class="fa fa-icon fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            {!! Form::select('unit_id[]', @$units, @$edit? $product->unit_id : [], ['placeholder' => __('frontend.select') . ' ' . __('frontend.unit'),'class' => 'form-control', 'required']) !!}
                            <input type="hidden" name="qty[]" value="1">
                            {!! Form::select('city_id[]', [0 => __('frontend.all_city')] + @$cities, @$edit? $product->city_id : [], ['placeholder' => __('frontend.select') . ' ' . __('frontend.city_or_province'),'class' => 'form-control mt-3', 'required']) !!}
                            <div class="row">
                                <div class="col-sm">
                                    <input type="text" name="distributor[]" id="" class="form-control price mt-3" placeholder="{{ __('frontend.distributor_price') }}">
                                </div>
                                <div class="col-sm">
                                    <input type="text" name="wholesaler[]" id="" class="form-control price mt-3" placeholder="{{ __('frontend.wholesaler_price') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm">
                                    <input type="text" name="retailer[]" id="" class="form-control price mt-3" placeholder="{{ __('frontend.retailer_price') }}">
                                </div>
                                <div class="col-sm">
                                    <input type="text" name="buyer[]" id="" class="form-control price mt-3" placeholder="{{ __('frontend.buyer_price') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm flex-row">
                                    <p class="mb-1 mt-2">{{__('frontend.show')}} 
                                        <span class="text-primary">({{__('frontend.note_set_price')}})</span> 
                                        <span class="pl-2">
                                            <input type="radio" class="mt-3 price_flage" name="is_flag" checked>
                                            <input type="hidden" value="1" name="price_flag[]">
                                        </span>
                                    </p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        <div class="for-group row">
            <div class="form-group col-sm-12 text-right">
                {!! Form::submit(__('frontend.save'), ['class' => 'btn btn-warning btn-submit']) !!}
                {{-- <a href="{!! route('product.index') !!}" class="btn btn-light">Cancel</a> --}}
            </div>
        </div>
    </div>
  </div>
  {{-- End section tab of Price --}}
</div>


@section('scripts')
<script>
    $(document).ready(function(){

    });

    $(document).on('click', '.add-name', function() {
        $(this).hide();
        $('.product-name').after(`
            <div class="form-group product-name">
                <div class="row">
                    <div class="col-sm d-flex">
                        <p class="mb-1">{{ __('frontend.name') }}</p>
                        <a class="btn btn-sm ml-auto rm-product-name"><i class="fa fa-icon fa-minus-circle" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <input type="text" name="name[]" placeholder="{{__('frontend.name_km')}}" class="form-control">
                        <input type="hidden" name="locale[]" value="km">
                        <textarea class="form-control mt-3" id="" name="description[]" cols="30" rows="3" placeholder="{{ __('frontend.descritpion_km') }}">{{ @$edit ? '' : '' }}</textarea>
                    </div>
                </div>
            </div>
        `);
    });

    $(document).on('click', '.add-price', function() {
        $(this).hide();
        $('.product-price').after(`
            <div class="form-group product-price mt-2">
                <div class="row">
                    <div class="col-sm d-flex">
                        <p class="mb-1">{{ __('frontend.unit_price') }} 2</p>
                        <a id="btn-add-name" class="btn btn-sm ml-auto remove-price"><i class="fa fa-icon fa-minus-circle" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        {!! Form::select('unit_id[]', @$units, @$edit? $product->unit_id : [], ['placeholder' => __('frontend.select') . ' ' . __('frontend.unit'),'class' => 'form-control select2', 'required']) !!}
                        <input type="hidden" name="qty[]" value="1">
                        {!! Form::select('city_id[]',[0 => __('frontend.all_city')] + @$cities, @$edit? $product->city_id : [], ['placeholder' => __('frontend.select') . ' ' . __('frontend.city_or_province'),'class' => 'form-control select2 mt-3', 'required']) !!}
                        <div class="row">
                            <div class="col-sm">
                                <input type="text" name="distributor[]" id="" class="form-control price mt-3" placeholder="{{ __('frontend.distributor_price') }}">
                            </div>
                            <div class="col-sm">
                                <input type="text" name="wholesaler[]" id="" class="form-control price mt-3" placeholder="{{ __('frontend.wholesaler_price') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm">
                                <input type="text" name="retailer[]" id="" class="form-control price mt-3" placeholder="{{ __('frontend.retailer_price') }}">
                            </div>
                            <div class="col-sm">
                                <input type="text" name="buyer[]" id="" class="form-control price mt-3" placeholder="{{ __('frontend.buyer_price') }}">
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-sm flex-row">
                            <p class="mb-1 mt-2">{{__('frontend.show')}} 
                                <span class="text-primary">({{__('frontend.note_set_price')}})</span> 
                                <span class="pl-2">
                                    <input type="radio" class="mt-3 price_flage" name="is_flag">
                                    <input type="hidden" value="0" name="price_flag[]">
                                </span>
                            </p>
                            
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        `);
    });

    $(document).on('click', '.next-1', function() {
        $('#nav-profile-tab').tab('show'); // Select tab by name
    });

    $(document).on('click', '.next-2', function() {
        $('#nav-contact-tab').tab('show'); // Select tab by name
    });

    // Validate form field which is not fill
    $(document).on('click', '.btn-submit', function() {
        let is_check = false;
        $('[name="name[]"]').each(function(key, ele) { 
            if (ele.value == '') {
                $(ele).addClass('is-invalid');  
                is_check = true;
            }
        });

        $('[name="description[]"]').each(function(key, ele) { 
            if (ele.value == '') {
                $(ele).addClass('is-invalid');  
                is_check = true;
            }
        });

        if (is_check) { // checking is not were fill on set up name
            $('#nav-home-tab').tab('show');
            return; 
        }

        $('.select2').each(function(key, ele) {
            if (ele.value == '') {
                $(ele).css('border', '1px solid red');  
                is_check = true;
            }
        });

        if (is_check) { // checking is not were fill on set up name
            $('#nav-profile-tab').tab('show'); 
            return; 
        }

        $('[name="unit_id[]"]').each(function(key, ele) {
            if (ele.value == '') {
                $(ele).css('border', '1px solid red');  
                is_check = true;
            }
        });

        $('[name="city_id[]"]').each(function(key, ele) {
            if (ele.value == '') {
                $(ele).css('border', '1px solid red');  
                is_check = true;
            }
        });

        $('.price').each(function(key, ele) { 
            if (ele.value == '') {
                $(ele).addClass('is-invalid');  
                is_check = true;
            }
        });

        if (is_check) { // checking is not were fill on set up name
            $('#nav-contact-tab').tab('show'); 
            return; 
        }
    });


    // remove error field
    $(document).on('change', '.form-control', function(){
        $(this).removeClass('is-invalid');  
    });

    $(document).on('change', 'select', function(){
        $(this).css('border', '1px solid #ced4da');  
    });
    // end remove err field

    $(document).on('click', '.rm-product-name', function () {
        $('#btn-add-name').show();
        $(this).closest('.product-name')[0].remove();
    });

    $(document).on('click', '.remove-price', function () {
        $('#btn-add-price').show();
        $(this).closest('.product-price')[0].remove();
    });

    $(document).on('click', '.price_flage', function() {
        $('[name="price_flag[]"]').val(0);
        $(this).closest('span').find('[name="price_flag[]"]').val(1);
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

	$(document).ready(function(){
		let is_edit = "{{ @$edit }}";

		if (is_edit) {
			let image = {!! json_encode(@$images)  !!};
			let preloaded = [];
			$.each(image, function(key, value){
				preloaded.push({
					id : key,
					src : "{{ asset('uploads/images/products/') }}"+ '/' +value,
				});
			});

			$('.input-images').imageUploader({
				preloaded: preloaded,
				imagesInputName: 'images',
				preloadedInputName: 'old',
				maxSize: 2 * 1024 * 1024,
				maxFiles: 10
			});
		} else {
			$('.input-images').imageUploader();
		}

	});

	
</script>
@endsection