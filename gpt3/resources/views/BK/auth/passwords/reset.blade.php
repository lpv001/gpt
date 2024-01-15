@extends('layouts.main_auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">



          <center>
            <a href="{{ url('/')}}">
              <img class="logo" src="{{ asset('img/gg_logo.png')}}">
            </a>
          </center>

          <div class="card">

          <article class="card-body">
          	<h4 class="card-title mb-4 mt-1">{{ __('Reset Password') }}</h4>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

              <div class="form-group input-icon">
              	<i class="fa fa-phone"></i>
                <input id="email" placeholder="Phone number" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
              </div> <!-- form-group// -->
              <div class="form-group input-icon">
              	<i class="fa fa-lock"></i>
                <input id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif

              </div> <!-- form-group// -->

              <div class="form-group input-icon">
              	<i class="fa fa-lock"></i>
                <input id="password-confirm" placeholder="Confirm password" type="password" class="form-control" name="password_confirmation" required>
             </div> <!-- form-group// -->


              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                        <button type="submit" class="btn btn-warning btn-block">
                            {{ __('Reset Password') }}
                        </button>
                      </div> <!-- form-group// -->
                  </div>
              </div> <!-- .row// -->
          </form>
          </article>
          </div> <!-- card.// -->


          {{--}}

            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            --}}



        </div>
    </div>
</div>
@endsection
