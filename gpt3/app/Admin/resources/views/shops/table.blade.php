<div class="table-responsive">
    <table class="table table-bordered" id="shops-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Shop Name</th>
            <th>Owner</th>
            <th>Supplier</th>
            <th>Phone</th>
            <th>City/Province</th>
            <th>District</th>
            <th>Membership</th>
            <th>Status</th>
            <th colspan="3">Action</th>
          </tr>
        </thead>
        
        @php
        $shop_category_list = [];
        $supplier_list = [];
        @endphp
        
        <tbody>
        @foreach($shops as $shop)
          <tr>
            <td>{!! $shop->id !!}</td>
            <td>{!! $shop->name !!}</td>
            <td>{!! @\App\Admin\Models\User::find($shop->user_id)->full_name !!}</td>
            <td>{!! preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '',
                      @\App\Admin\Models\Shop::find($shop->supplier_id)->name) !!}</td>
            <td>{!! preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '',
                      $shop->phone) !!}</td>
            <td>{!! @\App\Admin\Models\City::find($shop->city_province_id)->default_name !!}</td>
            <td>{!! @\App\Admin\Models\District::find($shop->district_id)->default_name !!}</td>
            <td>{!! @$shop->membership->name !!}</td>

            @if($shop->status == 1)
                  <td><span class="label label-success">Accepted</span></td>
            @elseif($shop->status == 10)
                  <td><span class="label label-danger">Rejected</span></td>
            @else
                  <td><span class="label label-warning">Pending</span></td>
            @endif

              <td>
                  {!! Form::open(['route' => ['shops.destroy', $shop->id], 'method' => 'delete']) !!}
                  <div class='btn-group'>
                      <a href="{!! route('shops.show', [$shop->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                      <a href="{!! route('shops.edit', [$shop->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                      {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                  </div>
                  {!! Form::close() !!}
              </td>
          </tr>

        @endforeach
        </tbody>
    </table>
    <center>
      {!! $shops->appends(request()->input())->links() !!}
    </center>
</div>
