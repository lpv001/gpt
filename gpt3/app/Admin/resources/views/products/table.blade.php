<div class="table-responsive">
    <table id="datatables" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th width="6%">ID</th>
                <th width="10%">Code</th>
                <th width="20%">English Name</th>
                <th width="20%">Khmer Name</th>
                <th width="8%">Shop</th>
                <th width="10%">Category</th>
                <th width="8%">Brand</th>
                <th width="10%">Image</th>
                <th width="8%" colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
          @php
            $category_list = [];
            $brand_list = [];
            $shop_list = [];
            $shop_str = '';
          @endphp
          
          @foreach($products as $product)
            @php
              $category_names = [];
              foreach ($product->categories as $cat) {
                  $category_names[] = $cat->default_name;
              }
              $category_str = implode("; ", $category_names);
              
              $brand_trans = DB::table('brand_translations')->where(['brand_id' => $product->brand_id, 'locale' => \App::getLocale()])->first();
              $brand_str = $brand_trans ? $brand_trans->name : '';
              
              if (array_key_exists($product->shop_id, $shop_list)) {
                $shop_str = $shop_list[$product->shop_id];
              } else {
                $select_shop = DB::table('shops')->where(['id' => $product->shop_id])->first();
                if ($select_shop) {
                  $shop_list[$product->shop_id] = $select_shop->name;
                  $shop_str = $select_shop->name;
                }
              }
              
            @endphp
            <tr>
              <td>{!! $product->id !!}</td>
              <td>{!! $product->product_code !!}</td>
              <td>{!! $product->name_en !!}</td>
              <td>{!! $product->name_km !!}</td>
              <td>{!! $shop_str !!}</td>
              <td>{!! $category_str !!}</td>
              <td>{!! $brand_str !!}</td>
              <td>
                @if($product->images)
                <img src="{{ asset('/uploads/images/products/thumbnail/small_' . $product->images->image_name)}}" height="70px" />
                @endif
              </td>
              {{--<td>{!! $product->is_active ? 'Enable' : 'Disable' !!}</td>--}}
              <td>
                    {!! Form::open(['route' => ['products.destroy', $product->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('products.show', [$product->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('products.edit', [$product->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
    </table>
    <center>
      {!! $products->appends(request()->input())->links() !!}
    </center>
</div>

