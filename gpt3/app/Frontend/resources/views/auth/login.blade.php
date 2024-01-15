@extends('frontend::layouts.main_auth')

@section('content')

@php
    if (session()->has('locale')) {
      \App::setLocale(session()->get('locale'));
    } else {
      \App::setLocale('en');
    }
@endphp

<div class="container">
    <div class="row justify-content-center">


        <div class="col-md-4">

          <center>
            <a href="{{ url('/')}}">
              <img class="logo-img" style="max-width: 40%" src="{{ asset('img/logo.png')}}">
            </a>
          </center>

          <div class="card">

          <article class="card-body">
          	{{--}}<a href="{{ url('register') }}" class="float-right btn btn-outline-warning">Sign up</a>--}}
          	<h4 class="card-title mt-3 text-center">{{ __('frontend.sign_in_account') }}</h4>
            {{--}}
            <p>
          		<a href="" class="btn btn-block btn-facebook"> <i class="fab fa-facebook-f"></i> &nbsp; Login via facebook</a>
          	</p>
          	<hr>
            --}}


            {{-- print_r($errors)--}}

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
              <div class="form-group input-icon has-danger">
              	<i class="fa fa-phone"></i>
                <input id="email" placeholder="Phone number" type="text" class="form-control number {{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required autofocus>

                  @if ($errors->has('phone'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('phone') }}</strong>
                      </span>
                  @endif
              </div> <!-- form-group// -->
                <input type="hidden" id="token" name="token">
              <div class="form-group input-icon">
              	<i class="fa fa-lock"></i>
                <input id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="password" required autocomplete>


                  @if ($errors->has('password'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('password') }}</strong>
                  @endif

                  @if($errors->any())
                        <br>
                        <p class="text-center text-danger ">{{$errors->first()}}</p>

                  @endif

                  <input type="hidden" name="fcm_token" value="" id="fcmToken">
              </div> <!-- form-group// -->
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-block">{{__('frontend.sign_in_account')}}</button>
                      </div> <!-- form-group// -->
                  </div>


                  <div class="col-md-12">

                      @if (Route::has('password.request'))

                        <a class="small" href="{{ route('password.request') }}">
                            {{ __('frontend.forget_your_password') }}
                        </a>
                      @endif
                      <hr>
                  </div>


                  <div class="col-md-12">
                    <center>
                      {{__('frontend.dont_have_an_account')}}? <a href="{{ url('register') }}">{{__('frontend.sign_up')}}</a>
                    </center>
                  </div>

              </div> <!-- .row// -->
          </form>
          </article>
          </div> <!-- card.// -->

        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js"></script>
<script src="{{ asset('js/firebase/firebase.js') }}"></script>

<script>
     $(document).ready(function () {
    //called when key is pressed in textbox
        $(".number").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                return false;
            }
        });
    });

    // Your web app's Firebase configuration
    var config = {
        apiKey: "AIzaSyBDqn3RogR-Cj2KFW4EnppkrskyflQbTeU",
        authDomain: "gangos-cambodia.firebaseapp.com",
        databaseURL: "https://gangos-cambodia.firebaseio.com",
        projectId: "gangos-cambodia",
        storageBucket: "gangos-cambodia.appspot.com",
        messagingSenderId: "145187966800",
        appId: "1:145187966800:web:a8557fdfdbd6ae5a0fa655"
    };
    // var config = {
		// 		apiKey: "AIzaSyC3lbewpgZzEgRCPM2qaQ8SXCMLQuFvgVI",
		// 		authDomain: "gangostesting.firebaseapp.com",
		// 		databaseURL: "https://gangostesting.firebaseio.com",
		// 		projectId: "gangostesting",
		// 		storageBucket: "gangostesting.appspot.com",
		// 		messagingSenderId: "882033418489",
		// 		appId: "1:882033418489:web:b70c188302a2ed35ac8228"
		// 	};
    // Initialize Firebase
    firebase.initializeApp(config);

    const messaging = firebase.messaging();
    messaging
        .requestPermission()
        .then(function () {
            console.log("Token1 is : " + messaging.getToken())
            //MsgElem.innerHTML = "Notification permission granted."
            //console.log("Notification permission granted.");

            //setTokenSentToServer(false);
            // get the token in the form of promise
            return messaging.getToken()
        })
        .then(function(token) {
            console.log("Token is : " + token)
            //alert(token);
            $('#token').val(token)
           // sendTokenToServer(token);
            console.log("Ajax Token is : " + token)
        })
        .catch(function (err) {
            //setTokenSentToServer(false);
            //ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
            //console.log("Unable to get permission to notify.", err);
        });

    // Send the Instance ID token your application server, so that it can:
    // - send messages back to this app
    // - subscribe/unsubscribe the token from topics
    function sendTokenToServer(token) {
        // TODO(developer): Send the current token to your server.
        console.log('isSent:'+ token);
        $.ajax({
            type:'POST',
            url:'/store-fcm-token',
            data:{token : token, _token: "<?php echo csrf_token(); ?>"},
            success:function(data){
                $("#msg").html(data);
            }
        });
    }
</script>
@endsection
