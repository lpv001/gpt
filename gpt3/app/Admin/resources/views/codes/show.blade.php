@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Code Detail
        </h1>
        @if (@$code->is_ready == 0)
        <h1 class="pull-right">
            <a class="btn btn-primary pull-right" style="margin-top: -30px" href="{!! route('codes.edit', [$code->id]) !!}">Generate Data</a>
        </h1>
        @endif
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row mt-4">
                    <div class="col-4">
                    ID: {!! $code->id !!}
                    </div>
                    <div class="col-8">
                    Created Date: {!! $code->created_at !!}
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                      @php
                      $format_data = unserialize($code->format_data);
                      
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
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                    @if ($code->is_ready == 2)
                        <a href="{!! route('codes.download', $code->id) !!}" class="btn btn-primary">Download Code Data</a>
                    @else
                        @php
                        $prg = 100 / $code->ndiff * $code->cdiff;
                        @endphp
                        Generating...{!! number_format($prg,2) . '% = ' . number_format($code->cdiff) !!} codes done!
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
