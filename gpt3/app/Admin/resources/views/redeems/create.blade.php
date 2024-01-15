@extends('admin::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Promotion Type
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body mx-4">
                <div class="row">
                        {!! Form::open(['route' => 'redeem.store','enctype'=>'multipart/form-data']) !!}
                            @include('admin::redeems.fields')
                        {!! Form::close() !!}  
                </div>
                
            </div>
        </div>
    </div>
    
<script>
    $(document).ready(function(){
            $('.input-images').imageUploader();
        })
</script>
@endsection


