<div class="row">
    <div class="col-sm-12">
        <fieldset class="scheduler-border">
          <form action="{{ route('shops.index') }}" method="GET">
            <legend class="scheduler-border">Filter</legend>
            <div class="col-sm-6 pl-0">
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label">ID :</label>
                    <div class="controls">
                        <input type="text" class="form-control" type="text" id="shop_id" name="shop_id" value="{!!Request::get('shop_id')!!}" placeholder="Shop ID" />
                    </div>
                </div>
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label">Name :</label>
                    <div class="controls">
                        <input type="text" class="form-control" type="text" id="name" name="name" value="{!!Request::get('name')!!}" placeholder="Shop Name" />
                    </div>
                </div>
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label">Owner :</label>
                    <div class="controls">
                        {!! Form::select('owner', @$owners, [Request::get('owner')], ['placeholder' => '-- Select Category --','class' => 'form-control select2']) !!}
                    </div>
                </div>
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label">Shop Category :</label>
                    <div class="controls">
                        {!! Form::select('shop_category_id', @$shop_categories, [Request::get('shop_category_id')], ['placeholder' => '-- Select Shop Category --','class' => 'form-control select2']) !!}
                    </div>
                </div>
            </div>
            
            <div class="col-sm-3 pl-0">
                <div class="control-group col-sm-12">
                    <div class="controls bootstrap-timepicker pull-left">
                        <h4 class="mt-0">New Shops : <span id="new_product">{{$countCreatedToday}}</span></h4>
                        <h4 class="mt-0">Total Shops: <span id="total_product">{{$countTotal}}</span></h4>
                    </div>
                </div>
                
                <div class="control-group col-sm-12 my-4">
                    <div class="pull-left">
                      <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary pull-right" href="{!! route('shops.create') !!}">Add New</a>
                    </div>
                </div>
            </div>
            
          </form>
        </fieldset>
    </div>
    
</div>

        