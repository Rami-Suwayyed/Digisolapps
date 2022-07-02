@extends("layouts.admin.app")
@section("page-title")
    {{__('dashboard')}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__('dashboard_company')}}</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__('dashboard')}}</a></li>
        </ul>
    </div>
@endsection

@section("content")

    <div class="row">
        @if(isPermissionsAllowed("control-website-digisol"))
            <div class="col-md-6 col-lg-3">
                <a style="  text-decoration: none" href="{{ route("admin.digisol.contact.index") }}">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-envelope-open fa-3x"></i>
                        <div class="info">
                            <h4>{{__("Digisol ContactUs")}}</h4>
                            <p><b id="ordersCanceled">{{$counter->contactus}}</b></p>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
@endsection

@section("scripts")

@endsection
