@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Digisol Home")}}</h1>
            <p>{{__("All Digisol Home")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("HGome")}}</a></li>
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
                        <div class="col-md-6 col-lg-3">
                            <a style="  text-decoration: none"  href="{{route("admin.digisol.home.title.index")}}">
                                <div class="widget-small primary coloured-icon"><i class="icon fa fa-user fa-3x"></i>
                                    <div class="info">
                                        <h4>{{__('Title')}}</h4>
                                        <p><b id="users"></b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a style="  text-decoration: none"  href="{{route("admin.digisol.home.body.index")}}">
                                <div class="widget-small warning coloured-icon"><i class="icon fas fa-list-ul fa-3x"></i>
                                    <div class="info">
                                        <h4>{{__('testimonials')}}</h4>
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
