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
                    <div class="col-12">
                        <table class="table table-borderless">
                            <thead>
                                <th width="20%">
                                    Code
                                </th>
                                <th width="80%" colspan="6"></th>
                            </thead>
                            @if (@$code)
                            <tbody>
                                <tr>
                                    <td>Create Date</td>
                                    <td>{{$code->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Format</td>
                                    <td>
                                    @php
                                    $format_data = unserialize($code->format_data);
                                    @endphp
                                    Format of X1 is set to : {{$format_data['x1']}}
                                    </td>
                                </tr>
                            </tbody>
                            @endif
                        </table>
                    </div>

                    {{--
                    <div class="col-12 mt-4">
                        <div class="col-sm-6 px-0">
                            <h3 class="text-uppercase">
                                Code Data
                            </h3>
                        </div>
                        ///Tabale
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
@endsection
