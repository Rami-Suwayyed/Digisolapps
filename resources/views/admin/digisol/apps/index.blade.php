@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Digisol Apps")}}</h1>
            <p>{{__("All  Apps ")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.dashboard.index")}}">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.dashboard.index")}}">{{__("Digisol")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Apps")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="buttons-group">
                <a href="{{route("admin.digisol.apps.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create Now App')}}</a>
            </div>
            <div class="tile">
                <div class="tile-body">
                    <div class="card-deck">
                        @foreach($apps as $app)

                            <div class="col-md-3">
                                <div class="card">
                                    <img class="card-img-top" src="{{  $app->getFirstMediaFile("icon")->url}}"  alt="{{$app->getNameAttribute()}}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$app->id}} - {{$app->getNameAttribute()}}</h5>
                                        <p>{{$app->Category->getNameAttribute()}}</p>
                                        <p class="card-text">{{ \Illuminate\Support\Str::limit($app->getDescriptionAttribute(), 50, '...') }}</p>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-muted">
                                            <a href="{{route("admin.digisol.apps.show", ["id" => $app->id])}}"><i class="fa fa-eye"></i> {{'View'}}</a>

                                            <a href="{{route("admin.digisol.apps.edit", ["id" => $app->id])}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                            <form action="{{route("admin.digisol.apps.destroy", $app->id)}}" method="post" id="delete{{$app->id}}" style="display: none" data-swal-title="{{__("Delete App")}}" data-swal-text="{{__("Are Your Sure To Delete This App ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__('the App has been deleted successfully')}}">@csrf @method("delete")</form>
                                            <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$app->id}}"><i class="far fa-trash-alt"></i></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
