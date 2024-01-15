@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Product Price
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row mx-auto">
                    {!! Form::open(['route' => 'productPrices.store']) !!}

                        @include('admin::product_prices.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.addMore').on('click', function(){
                let row = `
                <tr>
                        <td class="pt-4">1</td>
                        <td>
                            {!! Form::select('product_id[]', $product, @$unit->parent_id ?? [], ['placeholder' => 'Pick Product', 'class' => 'form-control select2 selected_product','style' =>'width:100% !important']) !!}
                        </td>
                        <td>
                            {!! Form::select('unit_id[]', $unit, @$unit->parent_id ?? [], ['placeholder' => 'Pick Unit', 'class' => 'form-control select2 unit', 'style' =>'width:100% !important']) !!}
                        </td>
                        <td>
                            {!! Form::select('city_id[]', $city,[], ['placeholder' => 'All', 'class' => 'form-control select2', 'style' =>'width:100% !important']) !!}
                        </td>
                        <td>
                            {!! Form::text('qty_per_unit[]', @$productPrice->qty_per_unit ?? 1, ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {!! Form::text('distributor[]', @$productPrice->distributor ?? '', ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {!! Form::text('wholesaler[]', @$item->wholesaler ?? '', ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {!! Form::text('retail[]', @$item->retail ?? '', ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {!! Form::text('buyer[]', @$item->buyer ?? '', ['class' => 'form-control number', 'required']) !!}
                        </td>
                        <td>
                            {!! Form::checkbox('flag[]', @$item->flag ?? 0, ['class' => 'form-control boolean', 'required']) !!}
                        </td>
                        <td>
                           <a class="btn btn-sm btn-light btn-remove"><i class="fa fa-minus"></i></a>
                        </td>
                    </tr>
                `;

                $('.table tbody').append(row);
                $('select').select2();

                $(function() {
            $.fn.inputFilter = function(inputFilter) {
                return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
                });
            };
            // Validate input Unit Price field 
            $(":text").inputFilter(function(value) {
                return /^-?\d*[.,]?\d*$/.test(value); 
            });
            // Validate input Sale Price field 
            $("#saleprice").inputFilter(function(value) {
                return /^-?\d*[.,]?\d*$/.test(value); 
            });
        });
            });

            $(document).delegate('.btn-remove', 'click', function(){
                $(this).closest('tr').remove();
            })
        });


        // $(document).delegate('.selected_product', 'change', function(){
        //     let unit = $(this).closest('tr').find('.unit');
        //     unit.html('');

        //     var route_name = "{{ route('productPrice.units', ['id']) }}";
        //     $.ajax({
        //         type:     "get",
        //         url:      route_name.replace('id', $(this).val() ),
        //         success: function (data) {
        //             let optEle = '';
        //             $.each(data, function(key, value) {
        //                 optEle += `<option value="${key}">${value}</option>`;
        //             });

        //             unit.html(optEle);
        //             $('select').select2();
        //         },
        //         error: function(xhr, status, error) {
        //             alert()
        //             unit.html('');
        //             unit.html('<option value="" disabled selected>Pick Unit</option>');
        //         }   
        //     });
        //     $.get( route_name.replace('id', $(this).val() ), function( data ) {
        //         let optEle = '';

        //         $.each(data, function(key, value) {
        //             optEle += `<option value="${key}">${value}</option>`;
        //         });

        //         unit.html(optEle);
        //         $('select').select2();
        //     });
        // });	
    </script>
@endsection
