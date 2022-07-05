@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Digisol About")}}</h1>
            <p>{{__("All Digisol About")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("About")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <a style="  text-decoration: none"  href="{{route("admin.digisol.about.first.index")}}">
                                <div class="widget-small warning coloured-icon"><i class="icon fa fa-battery-quarter fa-3x"></i>
                                    <div class="info">
                                        <h4>{{__('first paragraph')}}</h4>
                                        <p><b id="subjects"></b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a style="  text-decoration: none"  href="{{route("admin.digisol.about.second.index")}}">
                                <div class="widget-small warning coloured-icon"><i class="icon fa fa-battery-half fa-3x"></i>
                                    <div class="info">
                                        <h4>{{__('Second Paragraph')}}</h4>
                                        <p><b id="subjects"></b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a style="  text-decoration: none"  href="{{route("admin.digisol.about.third.index")}}">
                                <div class="widget-small warning coloured-icon"><i class="icon fa fa-battery-three-quarters fa-3x"></i>
                                    <div class="info">
                                        <h4>{{__('third Paragraph')}}</h4>
                                        <p><b id="subjects"></b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a style="  text-decoration: none"  href="{{route("admin.digisol.about.fourth.index")}}">
                                <div class="widget-small warning coloured-icon"><i class="icon fa fa-battery-full fa-3x"></i>
                                    <div class="info">
                                        <h4>{{__('fourth Paragraph')}}</h4>
                                        <p><b id="subjects"></b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
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
