<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <a class="app-sidebar__logo" href="{{ route("admin.dashboard.index") }}">
        <img src="{{asset("assets/Untitled.png")}}" alt="" class="site-logo full">
        <img src="{{asset("assets/Untitled.png")}}" alt="" class="site-logo small">
    </a>
    <div class="app-sidebar__user">
        <div class="avatar-box">
            <img class="app-sidebar__user-avatar" src="{{auth()->user()->profile_photo}}" alt="">
        </div>
        <div>
            <p class="app-sidebar__user-name">{{auth()->user()->full_name}}</p>
            {{--            <p class="app-sidebar__user-name" style="font-size: 14px; color: #f2f2f2">{{auth()->user()->isAdministrator() ? __("Admin") : auth()->user()->role->name}}</p>--}}
        </div>
    </div>
    <ul class="app-menu">

        @if(isPermissionsAllowed("view-dashboard"))
            <li><a class="app-menu__item @if(request()->routeIs("admin.dashboard.index")) active @endif" href="{{route("admin.dashboard.index")}}"><i class="app-menu__icon fas fa-tachometer-alt"></i><span class="app-menu__label">{{__("Dashboard")}}</span></a></li>
        @endif

        @if(isPermissionsAllowed("admin-control"))
            <!------------------------- Managers -------------------------->
            <li class="treeview @if(request()->routeIs("admin.managers.*") || request()->routeIs("admin.roles.*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-american-sign-language-interpreting"></i><span class="app-menu__label">{{__("Permissions")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->routeIs("admin.managers.*")) active @endif" href="{{ route("admin.managers.index") }}"><i class="icon fa fa-user-circle"></i> {{__("All Managers")}}</a></li>
                    <li><a class="treeview-item @if(request()->routeIs("admin.roles.*")) active @endif" href="{{ route("admin.roles.index")  }}"><i class="icon fa fa-cogs"></i> {{__("Roles & Permissions")}}</a></li>
                </ul>
            </li>
        @endif
        <!------------------------- notification -------------------------->
        @if(hasPermissions("control-website-digisol"))
            <li class="treeview @if(request()->routeIs("admin.digisol*")) is-expanded @endif"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-window-maximize"></i><span class="app-menu__label">{{__("Digisol")}}</span><i class="treeview-indicator fa @if(app()->getLocale() == "en") fa-angle-right @else fa-angle-left @endif"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item @if(request()->routeIs("admin.digisol.index*")) active @endif" href="{{ route("admin.digisol.index") }}"><i class="icon fa fa-book"></i> {{__("Digisol")}}</a></li>
                     <li><a class="treeview-item @if(request()->routeIs("admin.digisol.home*")) active @endif" href="{{ route("admin.digisol.home.index") }}"><i class="icon fa fa-archive"></i> {{__("Home")}}</a></li>
                   <li><a class="treeview-item @if(request()->routeIs("admin.digisol.social*")) active @endif" href="{{ route("admin.digisol.social.index") }}"><i class="icon fa fa-fire"></i> {{__("Social Media")}}</a></li>
                    <li><a class="treeview-item @if(request()->routeIs("admin.digisol.contact*")) active @endif" href="{{ route("admin.digisol.contact.index") }}"><i class="icon fa fa-envelope"></i> {{__("Contact Us")}}</a></li>
                   <li><a class="treeview-item @if(request()->routeIs("admin.digisol.settings*")) active @endif" href="{{ route("admin.digisol.settings.index") }}"><i class="icon fa fa-wrench"></i> {{__("Digisol settings")}}</a></li>
                    <li><a class="treeview-item @if(request()->routeIs("admin.digisol.apps*")) active @endif" href="{{ route("admin.digisol.apps.index") }}"><i class="icon fa fa-audio-description"></i> {{__("Digisol Apps")}}</a></li>
                </ul>
            </li>
        @endif
    </ul>
</aside>
