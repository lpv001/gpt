
@if (count($redeem_items) > 0)
    <div class="row">
        @foreach ($redeem_items as $item)
        <div class="col-sm-3 mb-4">
            <a href="#" class="cart-exchange" data-id="{{ $item->id }}" data-point="{{$item->value}}" data-img="{{ $item->image_url }}" data-name="{{ $item->name_en ?? "NO name" }}">
                <div class="d-flex">
                    <div class="card text-center" style="width: 16rem;">
                        <div class="d-flex justify-content-center">
                            <img class="card-img-top" style="margin:0; height: 11em !important;width: 11em !important;" src="{{$item->image_url}}" alt="Card image cap">
                        </div>
                        <div class="card-body py-2">
                        <h6 class="card-title">{{ $item->name_en ?? "NO name" }}</h6>
                        <h6 class="card-title">USD {{ $item->value ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </a>
            
        </div>
        @endforeach
    </div>
    
@endif


