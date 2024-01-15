<div class="alert alert-danger">
    {!! session('error') !!}
    @if (session('errorobj'))
        @php
        $ecode = session('errorobj');
        @endphp
        <div class="table-responsive">
            <table class="table mt-2 table-bordered" style="background: #ffdddd !Important;">
              <thead>
              <tr>
                  <th>X1</th><th>X2</th><th>N1</th><th>N2</th><th>N3</th><th>X3</th><th>N4</th><th>N5</th><th>N6</th><th>X4</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td>{!! $ecode->x1 !!}</td>
                <td>{!! $ecode->x2 !!}</td>
                <td>{!! $ecode->n1 !!}</td>
                <td>{!! $ecode->n2 !!}</td>
                <td>{!! $ecode->n3 !!}</td>
                <td>{!! $ecode->x3 !!}</td>
                <td>{!! $ecode->n4 !!}</td>
                <td>{!! $ecode->n5 !!}</td>
                <td>{!! $ecode->n6 !!}</td>
                <td>{!! $ecode->x4 !!}</td>
              </tr>
              </tbody>
            </table>
        </div>
    @endif
</div>
