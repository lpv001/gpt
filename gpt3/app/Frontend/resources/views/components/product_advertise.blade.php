@if (@$brand)
<div class="col-2 px-2 p-advertise">
    <a href="{{route('search.brand', $brand['id'])}}">
        <div class="card p-3" 
            style="
            margin: auto;
            border: 1px solid #f3f3f3;
            height: 10%;
            background-image: url('{{$brand['image_name']}}');
            background-repeat:no-repeat;
            background-size: 100% 100%;
            ">
        </div>
        <!--
        <div class="product-body " style="height: 80%;width:100%">
            <div class=""></div>
            {{-- <div class="p-price text-sm font-weight-bold text-center text-white">$ {{$product['unit_price']}}</div> --}}
        </div>
        -->
    </a>
</div>    
@endif