@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Website KadyTech")}}</h1>
            <p>{{__("All Website KadyTech")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("KadyTech")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="buttons-group">
{{--                <a href="{{route("admin.digisol.title.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create Website Title')}}</a>--}}
            </div>
            <div class="tile">
                <div class="tile-body">

                    <div class="row">
                        @if(isPermissionsAllowed("view-subjects"))
                            <div class="col-md-6 col-lg-3">
                                <a style="  text-decoration: none"  href="{{route("admin.KadyTech.index")}}">
                                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-archive fa-3x"></i>
                                        <div class="info">
                                            <h4>{{__('Home')}}</h4>
                                            <p><b id="users"></b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if(isPermissionsAllowed("control-website-digisol"))
                            <div class="col-md-6 col-lg-3">
                                <a style="  text-decoration: none"  href="{{ route("admin.digisol.settings.index") }}">

                                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-wrench fa-3x"></i>
                                        <div class="info">
                                            <h4>{{__('Settings')}}</h4>
                                            <p><b id="settings"></b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if(isPermissionsAllowed("control-website-digisol"))
                            <div class="col-md-6 col-lg-3">
                                <a style="  text-decoration: none" href="{{ route("admin.digisol.about.index") }}">
                                    <div class="widget-small danger coloured-icon"><i class="icon fa fa-exclamation fa-3x"></i>
                                        <div class="info">
                                            <h4>{{__("About Us")}}</h4>
                                            <p><b id="orders"></b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if(isPermissionsAllowed("control-website-digisol"))
                            <div class="col-md-6 col-lg-3">
                                <a style="  text-decoration: none" href="{{ route("admin.digisol.Services.index") }}">
                                    <div class="widget-small primary coloured-icon"><i class="icon fa fa-code-fork fa-3x"></i>
                                        <div class="info">
                                            <h4>{{__("Services")}}</h4>
                                            <p><b id="ordersCanceled"></b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if(isPermissionsAllowed("control-website-digisol"))
                            <div class="col-md-6 col-lg-3">
                                <a style="  text-decoration: none" href="{{ route("admin.digisol.social.index") }}">
                                    <div class="widget-small info coloured-icon"><i class="icon fa fa-at fa-3x"></i>
                                        <div class="info">
                                            <h4>{{__("Services")}}</h4>
                                            <p><b id="ordersCanceled"></b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if(isPermissionsAllowed("control-website-digisol"))
                            <div class="col-md-6 col-lg-3">
                                <a style="  text-decoration: none" href="{{ route("admin.digisol.apps.index") }}">
                                    <div class="widget-small success coloured-icon"><i class="icon fa fa-envelope fa-3x"></i>
                                        <div class="info">
                                            <h4>{{__("Apps")}}</h4>
                                            <p><b id="ordersCanceled"></b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if(isPermissionsAllowed("control-website-digisol"))
                            <div class="col-md-6 col-lg-3">
                                <a style="  text-decoration: none" href="{{ route("admin.digisol.contact.index") }}">
                                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-envelope fa-3x"></i>
                                        <div class="info">
                                            <h4>{{__("Contact Us")}}</h4>
                                            <p><b id="ordersCanceled"></b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")

    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset("assets/js/plugins/jquery.dataTables.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("assets/js/plugins/dataTables.bootstrap.min.js")}}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>


@endsection
