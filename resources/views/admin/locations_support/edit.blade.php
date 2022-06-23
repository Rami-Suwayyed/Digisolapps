@extends("layouts.admin.app")
@section("page-title")
    {{__("Support Locations")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Support Locations")}}</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.locations-support.index")}}"> {{__("Support Locations")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
@include("includes.dialog")
    <div class="row parent-box">
        <div class="col-lg-10 m-auto map-box">
            <div class="tile">
                <h3 class="tile-title">{{__("Edit Support Location")}}</h3>
                <div class="tile-body" id="parent-box">
                    <!--- Info Section --->

                    <div id="info-box">
                        <form method="post" action="{{ route("admin.locations-support.update",["location" => $location->id]) . (isset($_GET["redirect"]) ? "?redirect=" . $_GET["redirect"] : null) }}" enctype="multipart/form-data">
                            @csrf
                            @method("put")
                            <div id="location" class="text-with-icon">
                                <span class="icon color-primary-dark"><i class="fas fa-map-marker-alt"></i></span>
                                <span class="text">
                                    {{
                                        $location->country .
                                        (!empty($location->governorate)    ?   " - "  .  $location->governorate     : null) .
                                        (!empty($location->locality)       ?   " - "  .  $location->locality        : null) .
                                        (!empty($location->sub_locality)   ?   " - "  .  $location->sub_locality    : null) .
                                        (!empty($location->neighborhood)   ?   " - "  .  $location->neighborhood    : null)
                                    }}
                                </span>
                            </div>
                            <div class="form-group">

                                <div class="col-lg-6">
                                    <label class="control-label">{{__("Support")}}</label>
                                    <div class="toggle-flip">
                                        <label>
                                            <input type="checkbox" name="support" {{ checked("support", 1, $location) }}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="tile-footer">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{__("Save")}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
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

@endsection
