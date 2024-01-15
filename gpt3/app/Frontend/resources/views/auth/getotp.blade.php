@extends('frontend::layouts.main_auth')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <center>
                <a href="{{ url('/')}}">
                  <img class="logo" src="{{ asset('img/gg_logo.png')}}">
                  <!-- <img class="logo" src="{{ asset('img/gg_logo.png')}}"> -->
                </a>
            </center>
            <form  method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <input type="hidden" name="phone" value="{{@$phone??''}}"/>
                <input type="hidden" name="name" value="{{@$fullName??''}}"/>
                <input type="hidden" name="password" value="{{@$password??''}}"/>
                <input type="hidden" name="verify_request_id" value="{{@$verifyRequestId??''}}"/>
                <input type="hidden" id="token" name="fcm_token" value="{{@$fcmToken??''}}">
                <input type="hidden" name="otp_code" id="otpCode"/>
                <div class="card">
                    <article class="card-body">
                        {{--}}<a href="{{ url('register') }}" class="float-right btn btn-outline-warning">Sign up</a>--}}
                        <h4 class="card-title mt-3 text-center">{{ __('frontend.please_enter_code') }}</h4>
                            <p class="text-center">{{__('frontend.validatoin_code_just_received')}}</p>
                        {{-- <p class="text-center">the validation code you just received</p> --}}
                        {{-- print_r($errors)--}}
                        <div class="d-flex justify-content-around">
                            <div class="form-group mx-sm-1 mb-1">
                                <input type="text" class="form-control text-center" tabindex="1" id="text1" aria-describedby="text1" maxlength="1" required autofocus>
                            </div>

                            <div class="form-group mx-sm-1 mb-1">
                                <input type="text" class="form-control text-center" tabindex="2" id="text2" aria-describedby="text2" maxlength="1" required>
                            </div>

                            <div class="form-group mx-sm-1 mb-1">
                                <input type="text" class="form-control text-center" tabindex="3" id="text3" aria-describedby="text3" maxlength="1" required>
                            </div>

                            <div class="form-group mx-sm-1 mb-1">
                                <input type="text" class="form-control text-center" tabindex="4" id="text4" aria-describedby="text4" maxlength="1" required>
                            </div>
                        </div>
                        <br>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-warning btn-block" id="submit"> {{ __('Verify') }}</button>
                              <!-- <button type="submit" class="btn btn-warning btn-block">{{ __('Verify') }}</button> -->
                            </div> <!-- form-group// -->
                        </div>
                    </article>
                </div> <!-- card.// -->
            </form>
        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js"></script>


<script>
    
    var code1 = '';
    var code2 = '';
    var code3 = '';
    var code4 = '';

    var otpCode = '';
    console.log("Test notification")

    $("input").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $(document).on('click', 'input', function(){
        $(this).val('');
    });
    $(document).on('keyup', 'input', function(){

        let index = $(this).attr('tabindex');
        if (index == 1) $('#text2').focus();
        if (index == 2) $('#text3').focus();
        if (index == 3) $('#text4').focus();
    });

    $("#submit").click(function () {
        code1 = $("#text1").val();
        code2 = $("#text2").val();
        code3 = $("#text3").val();
        code4 = $("#text4").val();
        otpCode = code1 + code2 +code3 + code4;

        $('#otpCode').val(otpCode)

        console.log("otpcode " + otpCode);
    })

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
