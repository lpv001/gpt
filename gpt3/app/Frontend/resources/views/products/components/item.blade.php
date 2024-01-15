
@if (count($products) > 0)
@foreach($products as $key => $value)
    <div class="{{$classColumn ?? ''}}"> 
        <div class="row my-4">
                <div class="col-lg-12 col-md-6 col-sm-4 mx-auto text-center">
                        <div class="card shadow-sm border">
                            <a href="{{ route('product.show', ['id'=> $value['id']]) }}">
                                <div class="card-item" style="
                                /* background-image: url('{{$value['image_name']}}'); */
                                background-repeat:no-repeat;
                                background-size:contain;
                                border-radius: 3px;
                                max-width:100%;"
                                >
                                <img src="{{ $value['image_name'] ?? '' }}" alt="">

                                <div class="card-body px-3 pt-1 pb-0 text-left">
                                    <p class="title py-2 m-0">
                                            {{ $value['name'] }}
                                    </p>
                                </div>
                            </a>

                            <div class="card-body px-3 pt-0 text-left">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small href="#" class="text-secondary font-weight-bold shop-info" data-shop="{{$value['shop_id']}}">{{ $value['shop_name'] }}</small>
                                    <span class="m-0 text-danger font-weight-bold" style="">USD ${{ $value['unit_price'] }}</span>
                                </div>
                            </div>
                        </div>
                </div>
        </div>
    </div>  
@endforeach
@endif

@section('scripts')
    <script>
        $(document).on('click', '.shop-info', function() {
            let url = "{{ route('vist.shop', ['shop_id']) }}";
            window.location.href = url.replace('shop_id', $(this).data('shop'));
        });
    </script>
@endsection
