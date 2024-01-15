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
          	{{--}}<a href="{{ url('register') }}" class="float-right btn btn-outline-warning">Sign up</a>--}}
          	<h4 class="card-title mb-4 mt-1">{{ __('Login') }}</h4>
            {{--}}
            <p>
          		<a href="" class="btn btn-block btn-facebook"> <i class="fab fa-facebook-f"></i> &nbsp; Login via facebook</a>
          	</p>
          	<hr>
            --}}

            <form method="POST" action="{{ route('login') }}">
                @csrf
              <div class="form-group input-icon">
              	<i class="fa fa-phone"></i>
                <input id="email" placeholder="Phone number" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

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
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <button type="submit" class="btn btn-warning btn-block">{{ __('Login') }}</button>
                      </div> <!-- form-group// -->
                  </div>


                  <div class="col-md-12">

                      @if (Route::has('password.request'))

                        <a class="small" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                      @endif
                      <hr>
                  </div>


                  <div class="col-md-12">
                    <center>
                      Don’t have an account? <a href="{{ url('register') }}">Sign up</a>
                    </center>
                  </div>

              </div> <!-- .row// -->





          </form>
          </article>
          </div> <!-- card.// -->


          {{--


            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

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
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
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
