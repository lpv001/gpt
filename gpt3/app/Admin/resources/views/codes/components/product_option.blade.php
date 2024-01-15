@php
    $options = [];
    $count_image = 0;
    $option_value_index = [];
    $i = 0;
@endphp
<div id="dProductOption" style="display: {{ (@$product_options && count(@$product_options) > 0) ? 'block' : 'none'}}">
    <table class="table" id="productOption"> 
        {{-- style="display: none" --}}
        <thead class="thead-light">
            <tr>
                <th scope="col" width="5%">#</th>
                <th scope="col"  width="30%">Option</th>
                <th scope="col" width="40%">Value</th>
                <th scope="col" width="15%">Option Image</th>
                <th scope="col" width="10%"></th>
            </tr>
        </thead>
        <tbody id="product-options">
            @if (@$product_options && count(@$product_options) > 0)
                @foreach ($product_options as $key => $product_option)
                        @php
                            $option_values = [];
                            $index = ++$key; 
                            if (count(@$product_option->option->option_value) > 0) 
                            {
                                $options[$product_option->option->name] = [];
                                foreach ($product_option->option->option_value as $item) {
                                    if ($item->image) {
                                        $options[$product_option->option->name][] = [
                                            'option_id' => $item->id,
                                            'name'  => $item->name,
                                            'image_id'  =>  $item->image ? $item->image->id : 0,
                                            'image' => $item->image ? $item->image->image_name : ''
                                        ];
                                    }
                                    $option_values[$item->name] = $item->name;
                                    $option_value_index[$item->name] = $i++;
                                }
                            }
                        @endphp
                    <tr>
                        <td>
                            <span class="mt-4">{{$index}}</span>
                        </td>
                        <td>
                            <input type="text" class="form-control options option-validation" value="{{ @$product_option->option->name }}" name="options[]">
                        </td>
                        <td class="select-row">
                            
                            {!! Form::select('option_values_'.$index.'[]', $option_values, $option_values, ['class' => 'form-control select2 option-value option-validation', 'multiple' => 'multiple', 'id' => 'selectOption']) !!}

                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary btn-create-option-image">Option Image</a>
                        </td>
                        <td><a class="btn btn-sm btn-light btn-remove"><i class="fa fa-minus"></i></a></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        <span class="mt-4">1</span>
                    </td>
                    <td>
                        <input type="text" class="form-control options option-validation" name="options[]">
                    </td>
                    <td class="select-row">
                        {!! Form::select('option_values_1[]', [], [], ['class' => 'form-control select2 option-value option-validation', 'multiple' => 'multiple', 'id' => 'selectOption']) !!}
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary btn-create-option-image">Option Image</a>
                    </td>
                    <td><a class="btn btn-sm btn-light"><i class="fa fa-minus"></i></a></td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="my-2">
        <a href="#" class="btn btn-sm btn-light more-option border border-primary"><i class="fa fa-plus"></i> Add Option</a>
        <a href="#" class="btn btn-sm btn-light border border-primary" id="createVarient">Create Varient</a>
    </div>
    
    {{-- Option Image List --}}
    <div class="table-responsive" id="table-responsive">
        @foreach ($options as $key => $option)
            @if (count($option) > 0)
            <table class="table" id="{{$key}}" data-id="{{$key}}">
                <thead>
                    <tr>
                        <td>{{$key}}</td>
                        <td>Image</td>
                        <td></td>
                    </tr>
                </thead>
            <tbody id="tbody-{{$key}}">
            {{--dd($option_value_index)--}}
            @foreach ($option as $key_option => $option_item)
              <tr id="{{$key . '-' . $option_item['name']}}">
                  <td>{{$option_item['name']}}</td>
                  <td>
                      <div class="d-flex">
                          <div class="mr-2">
                              <img src="{{ asset('/uploads/images/products/' . $option_item['image'] ) }}" alt="" style="width: 70px; height:70px;">
                          </div>
                          <div class="custom-file">
                              <input type="file" accept="image/*" class="custom-file-input" name="option_images[{{$option_value_index[$option_item['name']]}}]" data-id="{{$option_item['image_id'] ?? 0}}"  {{@$option_item['image'] == '' ? 'required' : ''}}>
                              <label class="custom-file-label" for="customFile">Choose file</label>
                          </div>
                          <input type="hidden" value="{{$option_item['name']}}" name="option_value_images[]">    
                          <input type="hidden" value="{{$option_item['image_id']}}" name="option_value_old_image_id[]">
                          <input type="hidden" value="" name="option_value_delete_image_id[]">
                      </div>
                  </td>
                  <td>
                      <a href="#" class="btn btn-sm btn-danger pull-right btn-delete-option-image" data-id="{{$option_item['image_id'] ?? 0}}">
                          <span class="glyphicon glyphicon-trash"></span>
                      </a>
                  </td>
              </tr>
            @endforeach
            </tbody>
            </table>
            @endif
        @endforeach       
    </div>
    
    <div class="my-2" id="variant" style="display: {{@$variants ? '' : 'none'}}">
        <h5><strong>Preview Varients</strong></h5>
        {{-- <h6><strong>Product options to be create :</strong></h6> --}}
        <table class="table"> 
            <thead>
                <tr>
                    <th width="20%">Varient</th>
                    <th>Price</th>
                    {{-- <th>SKU</th> --}}
                    {{-- <th>Barcode</th> --}}
                    {{-- <th >Image</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody id="varientOption">
                @if (@$variants && count(@$variants))
                    @foreach ($variants as $item)
                        @php
                            $name = '';
                            $data = '';
                            if (count($item->variant_option_value) > 0) {
                                foreach ($item->variant_option_value as $key => $value) {
                                    $index = $key++;
                                    $name .=  $value->option_value ? $value->option_value->name : '';
                                    $name .= (count($item->variant_option_value) - 1) <= $index ? '' : ' / ';
                                    $data .= $value->option_value ? $value->option_value->name : '';
                                    $data .= (count($item->variant_option_value) - 1) <= $index ? '' : ',';
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                {{$name}}
                                <input type="hidden" value="{{$item->id}}" name="variant_id[]">
                                <input type="hidden" value="{{$data}}" name="varient[]">      
                            </td>
                            <td>
                                <input type="text" value="{{$item->price}}" name="varient_price[]" class="form-control number option-validation" placeholder="0.00">
                            </td>
                            {{-- <td>
                                <input type="file" class="form-control" accept="image/*" name="variant_images[]">
                                <input type="hidden" value="{{ $item->image ? $item->image->variant_id : null }}" name="variant_old_images[]" data-image="">
                            </td> --}}
                            <td><a class="btn btn-sm btn-danger btn-remove"><i class="fa fa-minus"></i></a></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    
</div>
