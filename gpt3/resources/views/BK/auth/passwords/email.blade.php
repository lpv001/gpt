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


            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

              <div class="form-group input-icon">
              	<i class="fa fa-phone"></i>
                <input id="email" placeholder="Phone number" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
              </div> <!-- form-group// -->

              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                        <button type="submit" class="btn btn-warning btn-block">
                            {{ __('Send Password Reset Link') }}
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
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
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
