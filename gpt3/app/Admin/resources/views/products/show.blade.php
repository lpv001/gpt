@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Product Detail
        </h1>

        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -30px" href="{!! route('productPrices.create') !!}">Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-borderless">
                            <thead>
                                <th width="10%">
                                    Product
                                </th>
                                <th colspan="6"></th>
                                
                            </thead>
                            @if (@$product)
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>{{$product->name}}</td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td>
                                    @foreach ($product->categories as $key => $category)
                                    {{$category->default_name}};
                                    @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Brand</td>
                                    <td>{{$product->brand->name ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td>Point Rate</td>
                                    <td>{{$product->point_rate ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>{{$product->description ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <td>Image</td>
                                    @php
                                        $image = asset('/uploads/images/products/'  . $product->images->image_name);
                                        // if file exist
                                        //$image = file_exists($file) ? $file : 'https://www.macedonrangeshalls.com.au/wp-content/uploads/2017/10/image-not-found.png' ;
                                    @endphp
                                    <td>
                                        <img src="{{ $image }}" alt="" srcset="" height="150" width="150">
                                    </td>
                                </tr>
                            </tbody>
                            @endif
                        </table>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="col-sm-6 px-0">
                            <h3 class="text-uppercase">
                                Product Price
                            </h3>
                        </div>
                        <table class="table table-borderless">
                            <thead>
                                <th>#</th>
                                <th>Unit</th>
                                <th>City</th>
                                <th>Distributor</th>
                                <th>Wholsaler</th>
                                <th>Retailer</th>
                                <th>Buyer</th>
                                <th>Flag</th>
                                <th width="5%">Action</th>
                            </thead>
                            <tbody>
                                @if (@$product_price)
                                    @foreach ($product_price as $key => $item)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ @$item->unit ? $item->unit->name : ''}}</td>
                                            <td>{{ @$item->city ? $item->city->default_name : 'All'}}</td>
                                            <td>{{ number_format($item->distributor, 2) }}</td>
                                            <td>{{ number_format($item->wholesaler, 2) }}</td>
                                            <td>{{ number_format($item->retailer, 2) }}</td>
                                            <td>{{ number_format($item->buyer, 2) }}</td>
                                            <td>{{ number_format($item->flag) }}</td>
                                            <td>
                                                <a href="{!! route('productPrices.edit', [$item->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit text-primary"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
