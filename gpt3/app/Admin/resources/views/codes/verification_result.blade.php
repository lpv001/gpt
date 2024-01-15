<div class="table-responsive">
    <table id="datatables" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th width="80%">data</th>
                <th width="20%" colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
          @foreach($codes as $code)
            <tr>
              <td>{!! $code->data !!}</td>
              <td>
                    {!! Form::open(['route' => ['codes.destroy', $code->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
    </table>
    <center>
      {!! $codes->appends(request()->input())->links() !!}
    </center>
</div>

