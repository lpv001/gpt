<div class="table-responsive">
    <table id="datatables" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th width="9%">X1</th>
                <th width="9%">X2</th>
                <th width="9%">N1</th>
                <th width="9%">N2</th>
                <th width="9%">N3</th>
                <th width="9%">X3</th>
                <th width="9%">N4</th>
                <th width="9%">N5</th>
                <th width="9%">N6</th>
                <th width="9%">X4</th>
                <th width="10%" colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
          @foreach($settings as $code)
            <tr>
              <td>{!! $code->x1 !!}</td>
              <td>{!! $code->x2 !!}</td>
              <td>{!! $code->n1 !!}</td>
              <td>{!! $code->n2 !!}</td>
              <td>{!! $code->n3 !!}</td>
              <td>{!! $code->x3 !!}</td>
              <td>{!! $code->n4 !!}</td>
              <td>{!! $code->n5 !!}</td>
              <td>{!! $code->n6 !!}</td>
              <td>{!! $code->x4 !!}</td>
              <td>
                    {!! Form::open(['route' => ['code-settings.destroy', $code->data], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('code-settings.show', [$code->data]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('code-settings.edit', [$code->data]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
    </table>
    <center>
      {!! $settings->appends(request()->input())->links() !!}
    </center>
</div>

