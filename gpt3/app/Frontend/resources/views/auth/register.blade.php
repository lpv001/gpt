@extends('frontend::layouts.main_auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

          <center>
            <a href="{{ url('/')}}">
              <img class="logo-img" style="max-width: 40%" src="{{ asset('img/logo.png')}}">
            </a>
            {{--}}
            <h2><a href="{{ url('/')}}">{{ env('APP_NAME')}}</a></h2>
            --}}
          </center>

<div class="card">
<article class="card-body">
	<h4 class="card-title mt-3 text-center">{{__('frontend.sign_up')}}</h4>
	{{--}}<p class="text-center">Get started with your free account</p>--}}
  {{--}}
	<p>
		<a href="" class="btn btn-block btn-facebook"> <i class="fab fa-facebook-f"></i> &nbsp; Login via facebook</a>
	</p>
	<p class="divider-text">
        <span>OR</span>
  </p>

--}}
{{-- print_r($errors)--}}

    <form method="POST" action="{{ route('get-otp') }}">
        @csrf
    	<div class="form-group input-group">
    		<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
    		 </div>
         <input id="name" placeholder="{{ __('frontend.full_name') }}" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
            <input type="hidden" id="token" name="fcm_token">
         @if ($errors->has('name'))
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $errors->first('name') }}</strong>
             </span>
         @endif
        </div> <!-- form-group// -->


        <div class="form-group input-group">
        	<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
    		</div>
 
        	<input id="phone" name="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }} number" placeholder="{{ __('frontend.phone_number') }}" type="tel" value="{{ old('phone') }}" required>

          @if ($errors->has('phone'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('phone') }}</strong>
              </span>
          @endif
{{--            @if (session()->has('nexmo')))--}}
                <span class="invalid-feedback" role="alert">
                  <strong>{{ session()->get('nexmo') }}</strong>
              </span>
{{--            @endif--}}

        </div> <!-- form-group// -->


        <div class="form-group input-group">
        	<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
    		</div>
            <input id="password" placeholder="{{ __('frontend.password') }}" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div> <!-- form-group// -->


        <div class="form-group">
            <button type="submit" class="btn btn-warning btn-block"> {{__('frontend.create_account')}}  </button>
        </div> <!-- form-group// -->
        <p class="text-center">{{__('frontend.have_an_account')}}? <a href="{{ url('/login')}}">{{__('frontend.sign_in_account')}}</a> </p>
    </form>
</article>
</div> <!-- card.// -->


        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js"></script>


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

    // console.log("Test notification")

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
