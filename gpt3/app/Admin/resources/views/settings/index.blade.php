@extends('admin::layouts.app')
@section('content')
    <section class="content-header">
        <h1>
            Setting
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body mx-4">
                <div class="row">
                    <div class="col-sm-6">
                        <h4>Product ( Point Rate )</h4>
                        <div class="row">
                            <div class="col-sm">
                                <div class="d-flex justify-content-start">
                                    <input type="text" id="productPoint" name="" class="form-control product-point number" value="{{@$setting_product_point['points_rate'] ?? 0}}" placeholder="0.00">
                                    <span class="bg-secondary py-2 px-4 text-white">Pts</span>
                                    <span class="ml-5 mt-2">=</span>

                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-start mb-3">
                                    <input type="text" id="pp-us" name="" class="form-control product-point number" value="{{@$setting_product_point['rate_us'] ?? 0}}" placeholder="0.00">
                                    <span class="bg-secondary py-2 px-4 text-white">$</span>
                                </div>
                                <div class="d-flex justify-content-start mb-3">
                                    <input type="text" id="pp-riel" name="" class="form-control product-point number" value="{{@$setting_product_point['rate_riel'] ?? 0}}" placeholder="0.00">
                                    <span class="bg-secondary py-2 px-4 text-white">៛ </span>
                                </div>

                                {{-- <div class="d-flex justify-content-end mt-4"> --}}
                                </div>
                            </div>
                        </div>
                    </div> {{--  end col-sm-6 --}}
                    <div class="col-sm-6"></div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="d-flex justify-content-between pl-5">
                            <span id="p-message"></span>
                            <a href="#" class="btn btn-sm btn-primary" id="btn-product-point">Save</a>
                        </div>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '#btn-product-point', function(e) {
            //
            e.preventDefault();
            let valid = false;

            $('.product-point').each(function(key, element) {
                $(element).addClass('is-invalid');
                
                if ($(element).val() == '') {
                    valid = false;
                    return false;
                } else {
                    $(element).removeClass('is-invalid');
                    valid = true;
                }
            });

            if (!valid) {
                return;
            }

            $('.product-point').removeClass('is-invalid');
            let product_point = {
                points_rate : $('#productPoint').val(),
                rate_us : $('#pp-us').val(),
                rate_riel : $('#pp-riel').val(),
            };

            product_point = JSON.stringify(product_point);

            $.post( "{{ route('setting.product-point') }}", {_token: "{{ csrf_token() }}", product_point:product_point}, function(response) {
                return response;
            })
            .done(function(response) {
                console.log(response);
                $('#p-message').removeClass('text-danger').addClass('text-success').text(response.message);
            })
            .fail(function(response) {
                $('#p-message').removeClass('text-success').addClass('text-danger').html(`<b>Something erros *</b> : ${response.responseJSON.message}`);
            })
            .always(function() {
                // alert( "finished" );
            });
        })
    </script>
@endsection