@if (@$product)
<div class="col-4" style="height: 190px">
    <a href="{{route('product.show', $product['id'])}}">
        <div class="card my-2 p-3" 
            style="
            border: 1px solid #f3f3f3;
            height: 80%; width:100%;
            background-image: url('{{$product['image_name']}}');
            background-repeat:no-repeat;
            background-size: 100% 100%;
            ">
        </div>
        <div class="product-body" style="height: 20%; width:100%">
            <div class="p-name my-1">{{$product['name']}}</div>
            <div class="p-price h5 font-weight-bold text-truncate">$ {{$product['unit_price']}} / {{$product['unit_name']}}</div>
        </div>
    </a>
</div>    
@endif