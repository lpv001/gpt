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

                    <h6 class="m-0 text-dark font-weight-bold" >US ${{$cart['price']}}</h6>

                    <div class="my-2 text-xs section-qty">
                        <a href="#" data-increa="false" class="btn-increa qty-decrea" data-id="{{$cart['id']}}" data-price="{{ $cart['price'] }}">
                            <i class="fa fa-minus-circle text-warning text-lg text-hover" aria-hidden="true"></i>
                        </a>

                        <span class="mx-1 qty font-weight-bold" data-qty="{{$cart['attributes']['qty']}}">{{$cart['attributes']['qty']}}</span>

                        <a href="#" data-increa="true" class="btn-increa qty-increa" data-id="{{$cart['id']}}" data-price="{{ $cart['price'] }}">
                            <i class="fa fa-plus-circle text-warning text-lg text-hover" aria-hidden="true"></i>
                        </a>
                    </div>
                    
                </div>
            </div>
        @endforeach
        {{-- <hr> --}}
        {{-- <div class=" d-flex justify-content-end">
            <div class="col-5">
                <div class="d-flex justify-content-between my-1">
                    <p class="text-muted text-sm m-0">Sub Total :</p>
                    <p class="text-muted text-sm m-0">US $<span class="sub-total">{{ number_format($sub_total,2)  ?? 0.00 }}</span></p>
                </div>
                <div class="d-flex justify-content-between my-1">
                    <p class="text-muted text-sm m-0">Delivery fees :</p>
                    <p class="text-muted text-sm m-0">US $0.00</p>
                </div>
                <div class="d-flex justify-content-between my-1">
                    <p class="text-muted text-sm m-0">Total :</p>
                    <p class="text-muted text-sm m-0">US $<span class="total" id="total">{{ number_format($total,2)  ?? 0.00 }}</span></p>
                </div>
            </div>
        </div> --}}
    @endif
</div>