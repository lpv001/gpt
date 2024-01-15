<div class="form-group col-sm-1 pull-right">
    {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
</div>

<div class="form-group col-sm-2 pull-right">
{!! Form::text('date', null, ['class' => 'form-control date', 'placeholder' => 'Date', 'autocomplete'=> 'off']) !!}
</div>

<div class="form-group col-sm-2 pull-right">
{!! Form::select('status', $status, null , ['class' => 'form-control', 'placeholder' => 'Status']) !!}
</div>

<div class="form-group col-sm-3 pull-right">
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Search']) !!}
</div>

<script type="text/javascript">
    $('.date').datepicker({  
       format: 'yyyy-mm-dd'
     });
</script>  
