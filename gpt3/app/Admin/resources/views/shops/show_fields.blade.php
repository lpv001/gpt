<div class="row mt-4">
    <div class="col-12">
        <table class="table table-borderless">
            <thead>
                <th width="10%">
                    Shop Detail
                </th>
                <th colspan="6"></th>
                
            </thead>
            <tbody>
                @if (@$shop)
                    <tr>
                        <td>Owner</td>
                        <td>: {{ getUserName($shop->user_id) ?  getUserName($shop->user_id)->full_name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Shop name</td>
                        <td>: {{$shop->name}}</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td class="text-capitalize">: {{ getMemberType($shop->membership_id) ?  getMemberType($shop->membership_id)->name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ getSupplier($shop->supplier_id) ?  getSupplier($shop->supplier_id)->name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>: {{$shop->phone}}</td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td>: 
                            <span>{{ getDistrict($shop->district_id) ? getDistrict($shop->district_id)->default_name : '' }} /</span>
                            <span>{{ getCityName($shop->city_province_id) ? getCityName($shop->city_province_id)->default_name : '' }} / </span>
                            <span>{{ getCountry($shop->country_id) ? getCountry($shop->country_id)->default_name : '' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>: {{$shop->address}}</td>
                    </tr>
                    <tr>
                        <td>About</td>
                        <td>: {{$shop->about}}</td>
                    </tr>
                    <tr>
                        <td>Geo Location</td>
                        <td>: 
                            <span>Lat : {{$shop->lat}} /</span>
                            <span>Lon : {{$shop->lng}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: 
                            @if($shop->status == 1)
                                <span class="label label-success">Accepted</span>
                            @elseif($shop->status == 10)
                                <span class="label label-danger">Rejected</span>
                            @else
                                <span class="label label-warning">Pending</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td>Images</td>
                        <td>
                            <div class="d-flex flex-row">
                                <div class="p-2">
                                    <b>Logo</b> 
                                    <div class="row m-2">
                                        <div class="col-6">
                                            <img src="https://www.macedonrangeshalls.com.au/wp-content/uploads/2017/10/image-not-found.png" alt="logo" width="150" height="150" srcset="">
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <b>Cover</b> 

                                    <div class="row m-2">
                                        <div class="col-6">
                                            <img src="{{ $shop->logo ? : 'https://www.macedonrangeshalls.com.au/wp-content/uploads/2017/10/image-not-found.png' }}" alt="logo" width="150" height="150" srcset="">
                                        </div>
                                    </div>
                                </div>
                              </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @php
        $lable_heading = '';
        if ($shop->membership_id == 2) {
            $lable_heading = 'Retailer';
        } elseif ($shop->membership_id == 3){
            $lable_heading = 'Wholesaler';
        } elseif ($shop->membership_id == 4){
            $lable_heading = 'Distributor';
        } else {
            $lable_heading = 'N/A';
        }
    @endphp

    <div class="col-12">
        <div class="col-6 px-0 my-5">
            <h3>My {{$lable_heading}}</h3>
        </div>
        <table id="mytable" class="table table-borderless" data-page-length='5'>
            <thead>
                <th width="5%">#</th>
                <th>Shop Name</th>
                <th>Owner</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
            </thead>
            <tbody>
                @if (@$my_suppliers)
                    @foreach ($my_suppliers as $key => $item)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{ getSupplier($item->id) ?  getSupplier($item->id)->name : 'N/A'}}</td>
                            <td>{{ getUserName($item->user_id) ?  getUserName($item->user_id)->full_name : 'N/A'}}</td>
                            <td>{{ $item->phone }}</td>
                            <td>
                                <span>{{ getDistrict($item->district_id) ? getDistrict($item->district_id)->default_name : '' }} /</span>
                                <span>{{ getCityName($item->city_province_id) ? getCityName($item->city_province_id)->default_name : '' }} / </span>
                                <span>{{ getCountry($item->country_id) ? getCountry($item->country_id)->default_name : '' }}</span>
                            </td>
                            <td>
                                @if($shop->status == 1)
                                    <span class="label label-success">Accepted</span>
                                @elseif($shop->status == 10)
                                    <span class="label label-danger">Rejected</span>
                                @else
                                    <span class="label label-warning">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{!! route('shops.show', [$shop->id]) !!}" class='btn btn-default btn-xs px-3'><i class="glyphicon glyphicon-eye-open"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>