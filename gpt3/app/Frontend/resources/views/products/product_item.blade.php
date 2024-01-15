@if (@$products)
    @foreach($products as $product)	
        <div class="col-md-4">
                <div class="card card-product w-100 mb-4" >
                    
                    <a href="{{ route('product.show', ['id' => $product['id'] ]) }}">
                        <div class="img-wrap" style="margin:0">
                                <img src="{{$product['image_name']}}" style="margin:0; height: 10em !important;width: 10em !important;" class="img-fluid w-100">
                        </div>
                    </a>

                    <div class="card-body">
                    <h6 class="title "><a href="{{ route('product.show', ['id'=>$product['id']]) }}">{{ $product['name'] }}</a></h6>
                        
                        <div class="price-wrap h5">
                        <a href="{{ route('product.show', ['id'=>$product['id']]) }}" class="btn btn-warning btn-bg float-right">
                            {{__('frontend.add_to_cart')}}
                        </a>
                    
                        
                        <span class="price-new">${{ $product['sale_price'] > 0 ?$product['sale_price'] : $product['unit_price'] }}</span>
                      
                        </div> 
                    </div>
                </div>
            </div> 
    @endforeach
    
@endif
