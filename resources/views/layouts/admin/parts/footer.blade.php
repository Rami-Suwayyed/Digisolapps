<div class="new-notifications-box">
    {{--    <a class="notification-box" href="#" style="display: block">--}}
    {{--        <div class="notification-header"><span class="icon"><i class="fas fa-bell fa-lg fa-rotate"></i></span><span class="text">New Notifications</span></div>--}}
    {{--        <div class="notification-body">Body</div>--}}
    {{--        <div class="notification-meta">5 Sec</div>--}}
    {{--    </a>--}}
    {{--    <a class="notification-box" href="#">--}}
    {{--        <div class="notification-header"><span class="icon"><i class="fas fa-bell fa-lg fa-rotate"></i></span><span class="text">New Notifications</span></div>--}}
    {{--        <div class="notification-body">Body</div>--}}
    {{--        <div class="notification-meta">5 Sec</div>--}}
    {{--    </a>--}}
</div>
<!-- Essential javascripts for application to work-->
<script src="{{asset("assets/js/jquery-3.2.1.min.js")}}"></script>
<script src="{{asset("assets/js/popper.min.js")}}"></script>
<script src="{{asset("assets/js/bootstrap.min.js")}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset("assets/js/main.js")}}"></script>
<script type="module" src="{{asset("assets/js/master.js")}}"></script>

<!-- The javascript plugin to display page loading on top-->
<script src="{{asset("assets/js/plugins/pace.min.js")}}"></script>
<script type="module" src="{{asset("assets/js/modules/helpers.js")}}"></script>
<script src="https://cdn.socket.io/4.4.0/socket.io.min.js" integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous"></script>    <script type="module">
    // import { io } from "socket.io-client";
</script>
<script type="module" src="{{asset("assets/js/utils/notifications.js")}}"></script>

<!-- Page specific javascripts-->

@hasSection("scripts")
    @yield("scripts")
@endif
