@extends('layouts.auth.app')

@section('content')
    @if(count($errors) > 0 )
        <div class="login-errors">
            <ul >
                @foreach($errors->all() as $key => $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="login-box">
        <form class="login-form"  method="post" action="{{ route('login') }}">
            @csrf
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
            <div class="form-group">
                <label class="control-label">USERNAME OR EMAIL</label>
                <input class="form-control" type="text" placeholder="Email" name="login" autofocus value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input class="form-control" id="token" type="hidden" name="token">
            </div>
            <div class="form-group">
                <label class="control-label">PASSWORD</label>
                <input class="form-control" type="password" placeholder="Password" name="password">
            </div>

            <div class="form-group">
                <div class="utility">
                    <div class="animated-checkbox">
                        <label>
                            <input type="checkbox"><span class="label-text">Stay Signed in</span>
                        </label>
                    </div>
                    <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p>
                </div>
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
            </div>
        </form>
        <form class="forget-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
            <div class="form-group">
                <label class="control-label">EMAIL</label>
                <input class="form-control" type="text" placeholder="Email" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
            </div>
            <div class="form-group mt-3">
                <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
            </div>
        </form>
    </div>
@endsection

@section("scripts")
    <script type="text/javascript">
        Notification.requestPermission().then(function(permission) {
            initFirebaseMessagingRegistration();
        }).catch(function (){
            console.log("denied");
        });
        // Login Page Flipbox control
        $('.login-content [data-toggle="flip"]').click(function() {
            $('.login-box').toggleClass('flipped');
            return false;
        });
    </script>
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyAwSTgxV6ZUWmqYEDw89Z65P6zX-J2bVyc",
            authDomain: "teacher-app-4a51b.firebaseapp.com",
            projectId: "teacher-app-4a51b",
            storageBucket: "teacher-app-4a51b.appspot.com",
            messagingSenderId: "597194163763",
            appId: "1:597194163763:web:c0a3027e82bf2411808546",
            measurementId: "G-2KS06RCEXT"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);
                    $("#token").val(token)
                }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
        }
    </script>
@endsection
