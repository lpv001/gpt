<div class="table-responsive">
    <table id="datatables" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th width="40%">Code</th>
                <th width="30%">Date</th>
            </tr>
        </thead>
        <tbody>
          @foreach($codes as $code)
            <tr>
              <td>{!! $req_code !!}</td>
              <td>{!! $code->created_at !!}</td>
            </tr>
          @endforeach
        </tbody>
    </table>
    <center>
      {!! $codes->appends(request()->input())->links() !!}
    </center>
</div>

