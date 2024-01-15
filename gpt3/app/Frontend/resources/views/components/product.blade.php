@if (count($products) > 0)
    @foreach ($products as $key => $product)

        <div class="item mr-2 shadow-sm product-card">
            <a href="{{route('product.show', $product['id'])}}">
                <img 
                class="card-img-top product-image" 
                src="{{$product['image_name'] ?? 'https://oscar-website-assets-production.s3.amazonaws.com/assets/camaleon_cms/image-not-found-4a963b95bf081c3ea02923dceaeb3f8085e1a654fc54840aac61a57a60903fef.png'}}" 
                {{-- style="" --}}
                alt="product/image">
            </a>

            <div class="my-2 p-2">
                <p class="title">{{$product['name']}}</p>

                <div class="d-flex justify-content-between align-items-center">
                    <small href="#" class="text-secondary font-weight-bold shop-info" data-shop="{{$product['shop_id']}}">{{ $product['shop_name'] }}</small>
                    <span class="m-0 text-danger font-weight-bold" style="">USD ${{ $product['unit_price'] }}</span>
                </div>
            </div>
        </div>
    @endforeach
    
@endif
