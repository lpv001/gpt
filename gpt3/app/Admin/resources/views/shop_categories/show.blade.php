@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Shop
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin::shops.show_fields')
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
               $('#mytable').DataTable();
        });
    </script>
@endsection
