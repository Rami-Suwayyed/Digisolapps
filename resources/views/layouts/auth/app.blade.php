<!DOCTYPE html>
<html>
@include("layouts.auth.parts.header")
@hasSection("css-links")
    @yield("css-links")
@endif
<body>
<section class="material-half-bg" style="background-image: url('{{asset("assets/bg4.jpg")}}')">
    <div class="cover" style="background-color: #0b2e13"></div>
</section>
<section class="login-content">
    <div class="logo">
        <img src="{{asset("assets/ostad-logo.svg")}}" alt="" class="site-logo login">
    </div>
    @yield("content")
</section>
<!-- Essential javascripts for application to work-->
@include("layouts.auth.parts.footer")
</body>
</html>
