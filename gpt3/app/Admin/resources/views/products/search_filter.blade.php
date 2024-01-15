<div class="row">
    <div class="col-sm-12">
        <fieldset class="scheduler-border">
          <form action="{{ route('products.index') }}" method="GET">
            <legend class="scheduler-border">Filter</legend>
            <div class="col-sm-6 pl-0">
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label" for="startTime">Code :</label>
                    <div class="controls">
                        <input type="text" class="form-control" type="text" id="code" name="code" value="{!!Request::get('code')!!}" placeholder="Product Code" />
                    </div>
                </div>
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label" for="startTime">Name :</label>
                    <div class="controls">
                        <input type="text" class="form-control" type="text" id="name" name="name" value="{!!Request::get('name')!!}" placeholder="Product Name" />
                    </div>
                </div>
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label" for="startTime">Category :</label>
                    <div class="controls">
                        {!! Form::select('category', @$categories, [Request::get('category')], ['placeholder' => '-- Select Category --','class' => 'form-control select2']) !!}
                    </div>
                </div>
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label" for="startTime">Brand :</label>
                    <div class="controls">
                        {!! Form::select('brand', @$brands, [Request::get('brand')], ['placeholder' => '-- Select Brand --','class' => 'form-control select2']) !!}
                    </div>
                </div>
            </div>
            
            <div class="col-sm-3 pl-0">
                <div class="control-group col-sm-12 my-2">
                    @php
                    $filter_options = [1 => 'With Options', 2 => 'With Option Images'];
                    @endphp
                    <label class="control-label input-label">Option :</label>
                    <div class="controls">
                        {!! Form::select('product_option', @$filter_options, [Request::get('product_option')], ['placeholder' => '-- Filter Option --','class' => 'form-control select2']) !!}
                    </div>
                </div>
                
                <div class="control-group col-sm-12 my-2">
                    <label class="control-label input-label">Shop :</label>
                    <div class="controls">
                        {!! Form::select('shop', @$shops, [Request::get('shop')], ['placeholder' => '-- Filter Shop --','class' => 'form-control select2']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-3 pl-0">
                <div class="control-group col-sm-12">
                    <div class="controls bootstrap-timepicker pull-left">
                        <h4 class="mt-0">New Products : <span id="new_product">{{$countProductCreatedToday}}</span></h4>
                        <h4 class="mt-0">Total Products: <span id="total_product">{{$countTotalProduct}}</span></h4>
                    </div>
                </div>
                
                <div class="control-group col-sm-12 my-4">
                    <div class="pull-left">
                      <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary pull-right" href="{!! route('products.create') !!}">Add New</a>
                    </div>
                </div>
            </div>
            
          </form>
        </fieldset>
    </div>
    
</div>

        