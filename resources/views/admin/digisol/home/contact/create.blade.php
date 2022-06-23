@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1> {{__("Website Descriptions")}}</h1>
            <p>{{__("Create Website Descriptions")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.digisol.description.index")}}">{{__("Website Descriptions")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.digisol.description.create')}}">{{__("Create")}}</a></li>
        </ul>
    </div>
@endsection

@section("css-links")
    <link rel="stylesheet" href="{{asset("assets/css/utils/week_days.css")}}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section("content")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Create New Website Descriptions")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.digisol.description.store")}}" enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English title  Website Descriptions")}}</label>
                                    <input class="form-control @if($errors->has('title_en')) is-invalid @endif" type="text" name="title_en" placeholder="{{__("Enter title English Website Descriptions")}}" value="{{inputValue("title_en")}}">
                                </div>
                                @error("title_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic title Website Descriptions")}}</label>
                                    <input class="form-control @if($errors->has('title_ar')) is-invalid @endif" type="text" name="title_ar" placeholder="{{__("Enter title Arabic Website Descriptions")}}" value="{{inputValue("title_ar")}}">
                                </div>
                                @error("title_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Website Descriptions")}}</label>
                                    <input class="form-control @if($errors->has('text_ar')) is-invalid @endif" type="text" name="text_ar" placeholder="{{__("Enter Arabic Website Descriptions")}}" value="{{inputValue("text_ar")}}">
                                </div>
                                @error("text_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Website Descriptions")}}</label>
                                    <input class="form-control @if($errors->has('text_en')) is-invalid @endif" type="text" name="text_en" placeholder="{{__("Enter English Website Descriptions")}}" value="{{inputValue("text_en")}}">
                                </div>
                                @error("text_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="tile-footer">
                            <button  type="submit" class="btn btn-primary">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Create")}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")





@endsection
