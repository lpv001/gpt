<div class="table-responsive">
    <table id="datatables" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th width="10%">No.</th>
                <th width="50%">Filename</th>
                <th width="40%">Ready to download</th>
            </tr>
        </thead>
        <tbody>
          @foreach($zfiles as $fid => $zfile)
            <tr>
              <td>{!! $fid !!}</td>
              <td>{!! $zfile !!}</td>
              <td>
                <a href="{!! route('codes.downloaddata', $code->id . '_' . $fid) !!}" class="btn btn-primary">Download Code Data</a>
              </td>
            </tr>
          @endforeach
        </tbody>
    </table>
</div>

