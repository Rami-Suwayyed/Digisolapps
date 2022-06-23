@extends("layouts.admin.app")
@section("page-title")
    Support Locations
@endSection
@section('css-links')
    <style>
        #info-box{display: none;}
    </style>
@endsection

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Support Locations")}}</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.locations-support.index")}}">{{__('Support Locations')}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__('Create')}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
    @include("includes.dialog")

    <div class="row parent-box">
        <div class="col-lg-10 m-auto map-box">
            <div class="tile">
                <h3 class="tile-title">{{__('Add Location')}}</h3>
                <div class="tile-body" id="parent-box">

                    <!--- Map Section --->

                    <div id="map-box" style="@if(Session::has("location_details")) display:none @endif">
                        <div id="map"></div>


                        <div class="tile-footer text-right">
                            <button class="btn btn-primary fetchAddressInfo" >{{__('Next')}}<i class="fa fa-fw fa-lg fa-arrow-right" style="margin-left: 7px;margin-right:0"></i></button>
                        </div>
                    </div>

                    <!--- Info Section --->

                    <div id="info-box" style="@if(Session::has("location_details")) display:block @endif">
                        <form method="post" action="{{ route("admin.locations-support.store") }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="latitude" id="lat" value="{{inputValue("latitude")}}">
                            <input type="hidden" name="longitude" id="lng" value="{{inputValue("longitude")}}">
                            <div id="location" class="text-with-icon">
                                <span class="icon color-primary-dark"><i class="fas fa-map-marker-alt"></i></span>
                                <span class="text">
                                    @if(Session::has("location_details"))
                                    {{ !empty(Session::get("location_details")["country"]) ? Session::get("location_details")["country"] : null }}
                                    {{ !empty(Session::get("location_details")["governorate"]) ? " - " . Session::get("location_details")["governorate"] : null }}
                                    {{ !empty(Session::get("location_details")["locality"]) ? " - " . Session::get("location_details")["locality"] : null }}
                                    {{ !empty(Session::get("location_details")["sub_locality"]) ? " - " . Session::get("location_details")["sub_locality"] : null }}
                                    {{ !empty(Session::get("location_details")["neighborhood"]) ? " - " . Session::get("location_details")["neighborhood"] : null }}
                                    @endif
                                </span>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-6">
                                    <label class="control-label">{{__("Supported")}}</label>
                                    <div class="toggle-flip">
                                        <label>
                                            <input type="checkbox" name="support" checked><span class="flip-indecator" data-toggle-on="{{__('ON')}}" data-toggle-off="{{__('OFF')}}"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="tile-footer">
                                <span class="btn btn-primary" id="CancelLocation"><i class="fa fa-fw fa-lg fa-times-circle"></i>{{__('Cancel')}}</span>
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{__('Add')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script src="{{asset("assets/js/maps.js")}}"></script>
    <script type="text/javascript">
        if(document.location.hostname == 'pratikborsadiya.in') {
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-72504830-1', 'auto');
            ga('send', 'pageview');
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{env("GOOGLE_API_KEY")}}&callback=initMap&libraries=&v=weekly"
        async
    ></script>

    <script type="module" src="{{asset("assets/js/pages/locations_support.js")}}"></script>
@endsection
