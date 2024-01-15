@if (@$product)
    <div class="product-card bg-white rounded mb-3 shadow-sm">
        <a href="{{route('product.show', $product['id'])}}">
            <div 
                style="
                margin: auto;
                height: 75%; width:75%;
                background-image: url('{{$product['image_name']}}');
                background-repeat:no-repeat;
                background-size: 100% 100%;
                ">
            </div>
            <div class="product-body px-2 pb-5" style="height: 25%; width:100%">
                <div class="p-name mb-1">{{$product['name']}}</div>
                <div class="p-price h5 font-weight-bold text-truncate">$ {{$product['unit_price']}} / {{$product['unit_name']}}</div>
            </div>
        </a>
    </div>
@endif

