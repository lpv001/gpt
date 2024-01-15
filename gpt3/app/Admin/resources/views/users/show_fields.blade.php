<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

        <!-- ID Field -->
        <div class="col-sm-12 form-group">
            {!! Form::label('id', 'ID:') !!}
            <p>{!! $user->id !!}</p>
        </div>

        <!-- Full Name Field -->
        <div class="col-sm-12 form-group">
            {!! Form::label('full_name', 'Full Name:') !!}
            <p>{!! $user->full_name !!}</p>
        </div>

        <!-- Phone Field -->
        <div class="col-sm-12 form-group">
            {!! Form::label('phone', 'Phone:') !!}
            <p>{!! $user->phone !!}</p>
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

        <div class="col-sm-12 form-group">
            <!-- Membership Id Field -->
            <div class="form-group">
                {!! Form::label('membership_id', 'Membership Id:') !!}
                <p>{!! @\App\Admin\Models\Membership::find($user->membership_id)->name !!}</p>
            </div>
        </div>

        <div class="col-sm-12 form-group">
            <!-- Membership Id Field -->
            <div class="form-group">
                {!! Form::label('created_at', 'Created At:') !!}
                <p>{!! $user->created_at !!}</p>
            </div>
        </div>

        <!-- Is Active Field -->
        <div class="col-sm-12 form-group">
            {!! Form::label('is_active', 'Status:') !!}
            @if($user->is_active == 1)
                <span class="label label-success">Active</span>
            @else
                <span class="label label-danger">InActive</span>
            @endif
        </div>
    </div>
</div>

<!-- Password Field -->
<!-- <div class="form-group">
    {!! Form::label('password', 'Password:') !!}
    <p>{!! $user->password !!}</p>
</div> -->


<!-- Phone Verify Field -->
<!-- <div class="form-group">
    {!! Form::label('phone_verify', 'Phone Verify:') !!}
    <p>{!! $user->phone_verify !!}</p>
</div> -->

<!-- Supplier Id Field -->
<!-- <div class="form-group">
    {!! Form::label('supplier_id', 'Supplier Id:') !!}
    <p>{!! $user->supplier_id !!}</p>
</div> -->

<!-- Remember Token Field -->
<!-- <div class="form-group">
    {!! Form::label('remember_token', 'Remember Token:') !!}
    <p>{!! $user->remember_token !!}</p>
</div> -->

<!-- Created At Field -->
<!-- <div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $user->created_at !!}</p>
</div> -->

<!-- Updated At Field -->
<!-- <div class="form-group"> -->
    <!-- {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $user->updated_at !!}</p>
</div> -->

<!-- Fcm Token Field -->
<!-- <div class="form-group">
    {!! Form::label('fcm_token', 'Fcm Token:') !!}
    <p>{!! $user->fcm_token !!}</p>
</div> -->

