    @foreach ($data as $key => $item)
    <div class="d-flex py-2 checkout-option justify-content-between" id="scope-payment">
        <div class="" >
            <input type="radio" class="{{ @$class_name }}" name="{{ @$input_name ?? '' }}" 
                value="{{ $item['id'] }}" 
                data-cost="{{ array_key_exists('cost', $item) ? $item['cost'] : 0 }}" 
                id="{{$item['slug']}}"
                data-flag="{{ array_key_exists('flag', $item) ? $item['flag'] : 0 }}" 
                @if ($item['id'] == old($input_name))
                 checked
                @endif
                @if (count($data) <= 1)
                    checked 
                @endif
                >
            <label for="{{$item['slug']}}" class="mb-0">{{ $item['name'] }}</label>
        </div>
        
        @if (array_key_exists('cost', $item))
           <span>
               US ${{ number_format($item['cost'], 2) }}
           </span>
        @endif
        
    </div>
    @endforeach
{{-- </div> --}}