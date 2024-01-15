@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Product Setting
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body mx-4">
                <h4>Product Prices</h4>
                <hr>
                
                {{--  --}}
                <div class="row">
                    <div class="col-sm-1">
                        <p class="mr-2 mt-2">Retailer</p>
                    </div>
                    <div class="col-sm-2">
                        <div class="d-flex flex-row bd-highlight mb-3">
                            <div class="form-group"> 
                                <div class="input-group">
                                <input type="text" class="form-control number product-price" value="{{ @$price_retailer->value ?? 0.00 }}" name="product-price[]" placeholder="0.00">
                                <div class="input-group-addon">%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  --}}
                <div class="row">
                    <div class="col-sm-1">
                        <p class="mr-2 mt-2">Wholesaler</p>
                    </div>
                    <div class="col-sm-2">
                        <div class="d-flex flex-row bd-highlight mb-3">
                            <div class="form-group"> 
                                <div class="input-group">
                                <input type="text" class="form-control number product-price" value="{{ @$price_wholesaler->value ?? 0.00 }}" name="product-price[]" placeholder="0.00">
                                <div class="input-group-addon">%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  --}}
                <div class="row">
                    <div class="col-sm-1">
                        <p class="mr-2 mt-2">Distributor</p>
                    </div>
                    <div class="col-sm-2">
                        <div class="d-flex flex-row bd-highlight mb-3">
                            <div class="form-group"> 
                                <div class="input-group">
                                <input type="text" class="form-control number product-price" value="{{ @$price_distributor->value ?? 0.00 }}" name="product-price[]" placeholder="0.00">
                                <div class="input-group-addon">%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  --}}
                <div class="row">
                    <div class="col-sm-6 d-flex flex-row bd-highlight">
                      <a href="#" class="btn btn-sm btn-primary" id="btnProductPrice">Save <span class="glyphicon glyphicon-refresh ml-1" class="reload"></span></a>

                      <span class="mt-2 ml-5 invisible load-text">Saving...</span>
                    </div>
                    <div class="col-sm-2">
                    </div>
                </div>
                {{--  --}}
                <hr>
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
    <script>
        $(document).on('click', '#btnProductPrice', function(event) {
            event.preventDefault();
            let ele = $(".glyphicon-refresh");
            animationRotate(ele, 360, 1000);
            $('.load-text').removeClass('invisible').addClass('visible');

            let productPrice = [];
            $('[name="product-price[]"]').each( function(key, ele) {
                productPrice.push(ele.value ? ele.value : 0.00);
            });

            $.ajax({
                /* the route pointing to the post function */
                url: '{{ route('promotion.store') }}',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {
                    value : productPrice,
                    promotion_type_id:1,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (response) { 
                    if (response.status) {
                        $('.load-text').html(response.message).css({"color": "#4CAF50"});
                    } else {
                        $('.load-text').html(response.message).css({"color": "#BB2B2B"});
                    }
                },
                error: function (request, status, error) {
                    console.log(request);
                    console.log(status);
                    console.log(error);
                }
            });
        });

        $(document).on('keyup', '.number', function() {
            let value = $(this).val() ? parseFloat($(this).val()) : 0.00;

            if (value > 100) {
                $(this).val(100);
                return false;
            }
        });

        function animationRotate(element, degree, duration) {
            $({deg: 0}).animate({deg: degree}, {
                duration: duration,
                step: function(now){
                    element.css({
                        transform: "rotate(" + now + "deg)"
                    });
                }
            });
        }
    </script>
@endsection