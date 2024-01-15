<div class="table-responsive">
    <table id="datatables" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th width="4%">ID</th>
                <th width="12%">Generate Date</th>
                <th width="60%">Format</th>
                <th width="15%">Ready to download</th>
                <th width="9%" colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
          @foreach($codes as $code)
            <tr>
              <td>{!! $code->id !!}</td>
              <td>{!! $code->created_at !!}</td>
              
              <td>
              @php
              $format_data = unserialize($code->format_data);
              $gen_progress = unserialize($code->gen_progress);
              
              @endphp
                   The total number of differences is: {!! number_format($format_data['ndiff']) !!} codes. Files start with {!! $code->file_prefix !!}*.txt.
                  <table class="table mt-4">
                      <thead>
                      <tr>
                          <th>X1</th><th>X2</th><th>N1</th><th>N2</th><th>N3</th><th>X3</th><th>N4</th><th>N5</th><th>N6</th><th>X4</th>
                      </tr>
                      </thead>
                      <tbody>
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
                      </tr>
                      </tbody>
                  </table>
              </td>
              
              <td>
                @if ($code->is_ready == 4)
                    <a href="{!! route('codes.download', $code->id) !!}" class="btn btn-primary">Download Code Data</a>
                @else
                    @php
                    $prg = 100 / $code->ndiff * $code->cdiff;
                    @endphp
                    @if ($code->is_ready == 0)
                    Code is waiting to be generated.
                    @endif
                    @if ($code->is_ready == 1)
                    Generating...<br>{!! number_format($prg,2) . '%. ' . number_format($code->cdiff) !!} codes done!
                    @endif
                    @if ($code->is_ready == 2)
                        @php
                        if (!isset($gen_progress['nfile'])) {
                            $gen_progress['nfile'] = 0;
                        }
                        $ran_prg = 100 / $gen_progress['cfile'] * $gen_progress['nfile'];
                        @endphp
                        <br>Randomizing {!!number_format($ran_prg,2) . '%. '!!}
                    @endif
                @endif
              </td>
              <td>
                    {!! Form::open(['route' => ['codes.destroy', $code->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('codes.show', [$code->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('codes.edit', [$code->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        
                        @php
                        $roles = [];
                        if (session('roles')!==null) {
                            $roles = session('roles');
                        }
                        $perm = 0;
                        foreach ($roles as $r) {
                            if ($r == 1) {
                                $perm = 1;
                            }
                        }
                        @endphp
                        @if ($perm == 1)
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        @endif
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

