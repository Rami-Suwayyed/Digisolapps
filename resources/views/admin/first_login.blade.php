<!DOCTYPE html>
<html>
@include('layouts.auth.parts.header')
<style>
    .mainDiv {
    display: flex;
    min-height: 100%;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    font-family: 'Open Sans', sans-serif;
  }
 .cardStyle {
    width: 500px;
    border-color: white;
    background: #fff;
    padding: 36px 0;
    border-radius: 4px;
    margin: 30px 0;
    box-shadow: 0px 0 2px 0 rgba(0,0,0,0.25);
  }
#signupLogo {
  max-height: 100px;
  margin: auto;
  display: flex;
  flex-direction: column;
}
.formTitle{
  font-weight: 600;
  margin-top: 20px;
  color: #2F2D3B;
  text-align: center;
}
.inputLabel {
  font-size: 15px;
  color: 004000#;
  margin-bottom: 6px;
  margin-top: 24px;
}
  .inputDiv {
    width: 70%;
    display: flex;
    flex-direction: column;
    margin: auto;
  }
input {
  height: 40px;
  font-size: 16px;
  border-radius: 4px;
  border: none;
  border: solid 1px #ccc;
  padding: 0 11px;
}
input:disabled {
  cursor: not-allowed;
  border: solid 1px #eee;
}
.buttonWrapper {
  margin-top: 40px;
}
  .submitButton {
    width: 70%;
    height: 40px;
    margin: auto;
    display: block;
    color: #fff;
    background-color: #1e7e34;
    border-color: #065492;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
  }
.submitButton:disabled,
button[disabled] {
  border: 1px solid #cccccc;
  background-color: #cccccc;
  color: #666666;
}

#loader {
  position: absolute;
  z-index: 1;
  margin: -2px 0 0 10px;
  border: 4px solid #f3f3f3;
  border-radius: 50%;
  border-top: 4px solid #666666;
  width: 14px;
  height: 14px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<body>
    <section>
        <!-- Image and text -->
        <nav class="navbar navbar-light" style="background-color: #1e7e34">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/W.png') }}" width="50" height="50" class="d-inline-block align-top" alt="">
            </a>
            <ul class="app-nav">
                <!-- User Menu-->
                <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>

                    <ul class="dropdown-menu settings-menu">
                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-lg"></i> Logout</a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </li>

            </ul>

        </nav>
    </section>
    <section class="material-half-bg">
        <div class="mainDiv">
            <div class="cardStyle">
              <form action="{{route('admin.login.completed')}}" method="post" id="signupForm">
                @csrf
                @method("put")
                <img src="{{asset("assets/ostad-logo.svg")}}" id="signupLogo"/>
                <h2 class="formTitle">
                    Change Password
                </h2>

              <div class="inputDiv">
                <label class="inputLabel" for="password">New Password</label>
                <input  class="form-control @if($errors->has('password')) is-invalid @endif" type="password" id="password" name="password"  required autocomplete="off">
                @error("password")
                <span style="color: red">{{$message}}</span>
                @enderror
              </div>

              <div class="inputDiv">
                <label class="inputLabel" for="confirmPassword">Confirm Password</label>
                <input class="form-control @if($errors->has('confirm_password')) is-invalid @endif"  type="password" id="confirmPassword" name="confirm_password" autocomplete="off">
                @error("confirm_password")
                <span style="color: red">{{$message}}</span>
                @enderror
              </div>
              <div class="buttonWrapper">
                <button type="submit" id="submitButton" onclick="validateSignupForm()" class="submitButton pure-button pure-button-primary">
                  <span>Continue</span>
                </button>
              </div>

            </form>
            </div>
          </div>
    </section>
    <!-- Essential javascripts for application to work-->
    @include('layouts.auth.parts.footer')
</body>

</html>
