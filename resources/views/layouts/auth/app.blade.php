<!DOCTYPE html>
<html>
@include("layouts.auth.parts.header")
@hasSection("css-links")
    @yield("css-links")
@endif
<body>
<section class="material-half-bg" style="background-image: url('{{asset("assets/Group10691.png")}}'); background-size: cover">
{{--    <div class="cover" style="background-color: wheat"></div>--}}
</section>
<section class="login-content">
    <div class="logo">
        <img src="{{asset("assets/Untitled.png")}}" alt="" class="site-logo login">
    </div>
    @yield("content")
</section>
<!-- Essential javascripts for application to work-->
@include("layouts.auth.parts.footer")
</body>
</html>
