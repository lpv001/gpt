<div class="row">
{{dd("kjjjj")}}
    <div class="col-sm-12">
        <fieldset class="scheduler-border">
          <form action="{{ route('code-data.index') }}" method="GET">
            <legend class="scheduler-border">Filter</legend>
            <div class="col-sm-6 pl-0">
                <div class="control-group col-sm-6 my-2">
                    <label class="control-label input-label" for="startTime">Code Data :</label>
                    <div class="controls">
                        <input type="text" class="form-control" type="text" id="code" name="code" value="{!!Request::get('code')!!}" placeholder="Code" />
                    </div>
                </div>
            </div>
            
            <div class="col-sm-3 pl-0">
                <div class="control-group col-sm-12 my-4">
                    <div class="pull-left">
                      <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                    </div>
                </div>
            </div>
            
          </form>
        </fieldset>
    </div>
    
</div>

        