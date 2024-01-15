<div class="card card p-4 my-4">
    <h5 class="font-weight-bold">{{ __('frontend.order_items') }}</h5>
    @if ($carts)
        @foreach ($carts as $cart)
            <input type="hidden" name="carts[]" value="{{ $cart['id'] }}">
            <div class="row my-2">
                <div class="col-3">
                    <img src="{{$cart['attributes']['image']}}" alt="" class="w-100 img-thumail rounded">
                </div>
                <div class="col-9">
                    <h6 class="m-0">
                        {{$cart['name']}}
                    </h6>

                    <p class="option-text mt-1 mb-2">
                        @if ($cart['attributes']['option'] > 0)
                            @foreach ($cart['attributes']['option'] as $key => $item)
                                <span class="font-weight-bold">{{$key}}</span> : <span class="text-capitalize">{{$item['name']}}</span>  
                                    @if ($key == 'Color' || $key == 'color')
                                        <span>
                                            <i class="fa fa-circle" style="color: {{$item['name']}}"  aria-hidden="true"></i>
                                        </span>
                                    @endif
                                <span class="mr-2"></span>
                            @endforeach
                        @endif
                    </p>

                    <h6 class="m-0 text-dark font-weight-bold" >
                    ${{ $cart['price'] }}
                    <span class="mx-1 qty font-weight-bold" data-qty="{{$cart['attributes']['qty']}}"> 
                    x {{ $cart['attributes']['qty'] }} = ${{ ($cart['attributes']['qty'] * $cart['price']) }}
                    </span>
                    </h6>
                </div>
            </div>
        @endforeach
    @endif
</div>