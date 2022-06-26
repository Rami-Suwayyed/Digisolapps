@extends("layouts.admin.app")
@section("page-title")
{{__("Profile")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Digisol")}}</h1>
            <p>{{__("Apps")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Digisol")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.digisol.apps.index")}}">{{__("Apps")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Show")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row user">
        <div class="col-md-10">
            <div class="tab-content">
                <div class="tab-pane active" id="user-timeline">
                    <div class="timeline-post">
                        <div class="post-media"><a href="#"><img src="{{ $app->getFirstMediaFile("icon")->url}}" width="75" style="border-radius: 50%;"></a>
                            <div class="content">
                                <h5><a href="#">{{$app->id}}</a></h5>
                                <p class="text-muted"><small>{{$app->getNameAttribute()}}</small></p>
                            </div>
                        </div>
                        <div class="post-content">
                            <p> {{$app->getNameAttribute()}}</p>
                            <p> {{$app->getDescriptionAttribute()}}</p>
                        </div>
                        <ul class="post-utility">
                            <li class="likes"><a href="#"></a></li>
                            <li class="shares"> <a href="#"><img src="{{ $app->getFirstMediaFile("phone")->url}}" width="75" style="border-radius: 50%;"></a></li>
                            <li class="comments"><img src="{{ $app->getFirstMediaFile("background")->url}}" width="75" style="border-radius: 50%;">
                              </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")

@endsection
