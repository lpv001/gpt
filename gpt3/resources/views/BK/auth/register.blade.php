@extends('layouts.main_auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

          <center>
            <a href="{{ url('/')}}">
              <img class="logo" src="{{ asset('img/gg_logo.png')}}">
            </a>
            {{--}}
            <h2><a href="{{ url('/')}}">{{ env('APP_NAME')}}</a></h2>
            --}}
          </center>

<div class="card">
<article class="card-body">
	<h4 class="card-title mt-3 text-center">Sign Up</h4>
	<p class="text-center">Get started with your free account</p>
  {{--}}
	<p>
		<a href="" class="btn btn-block btn-facebook"> <i class="fab fa-facebook-f"></i> &nbsp; Login via facebook</a>
	</p>
	<p class="divider-text">
        <span>OR</span>
  </p>

--}}

    <form method="POST" action="{{ route('register') }}">
        @csrf
    	<div class="form-group input-group">
    		<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
    		 </div>
         <input id="name" placeholder="Full name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

         @if ($errors->has('name'))
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $errors->first('name') }}</strong>
             </span>
         @endif
        </div> <!-- form-group// -->


        {{--}}

        <div class="form-group input-group">
        	<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
    		 </div>
         <input id="email" placeholder="Email address" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

         @if ($errors->has('email'))
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $errors->first('email') }}</strong>
             </span>
         @endif


        </div> <!-- form-group// -->

        --}}

        <div class="form-group input-group">
        	<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
    		</div>
    		<select class="custom-select" style="max-width: 80px;">
    		    <option selected="">+855</option>

    		</select>
        	<input name="phone" class="form-control" placeholder="Phone number" type="text">
        </div> <!-- form-group// -->

        {{--}}
        <div class="form-group input-group">
        	<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-building"></i> </span>
    		</div>
    		<select class="form-control">
    			<option selected=""> Select job type</option>
    			<option>Designer</option>
    			<option>Manager</option>
    			<option>Accaunting</option>
    		</select>
    	</div> <!-- form-group end.// -->
      --}}


        <div class="form-group input-group">
        	<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
    		</div>
            <input id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif


        </div> <!-- form-group// -->

        {{--}}
        <div class="form-group input-group">
        	<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
    		</div>
            <input id="password-confirm" placeholder="Confirm password" type="password" class="form-control" name="password_confirmation" required>

        </div> <!-- form-group// -->
        --}}


        <div class="form-group">
            <button type="submit" class="btn btn-warning btn-block"> Create Account  </button>
        </div> <!-- form-group// -->
        <p class="text-center">Have an account? <a href="{{ url('/login')}}">Log In</a> </p>
    </form>
</article>
</div> <!-- card.// -->


            {{--

            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

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
                                    {{ __('Register') }}
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
