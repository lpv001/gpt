


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
                        


