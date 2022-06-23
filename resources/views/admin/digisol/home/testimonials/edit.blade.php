@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Says About Us")}}</h1>
            <p>{{__("Says About Us")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.digisol.about-us.index")}}">{{__("Says About Us")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Edit Says About Us")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.digisol.about-us.update", ["id" => $saysAboutUS->id])}}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Title")}}</label>
                                    <input class="form-control @if($errors->has('title_en')) is-invalid @endif" type="text" name="title_en" placeholder="{{__("Enter English Title")}}" value="{{$saysAboutUS->title_en}}">
                                </div>
                                @error("title_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Title")}}</label>
                                    <input class="form-control @if($errors->has('title_ar')) is-invalid @endif" type="text" name="title_ar" placeholder="{{__("Enter Arabic Title")}}" value="{{$saysAboutUS->title_ar}}">
                                </div>
                                @error("title_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Description")}}</label>
                                    <textarea name="description_en" class="form-control @if($errors->has('description_en')) is-invalid @endif" cols="30" rows="10" placeholder="{{__("Enter English Description")}}" >{{$saysAboutUS->description_en}}</textarea>
                                </div>
                                @error("description_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Description")}}</label>
                                    <textarea name="description_ar" class="form-control @if($errors->has('description_ar')) is-invalid @endif" cols="30" rows="10" placeholder="{{__("Enter Arabic Description")}}" >{{$saysAboutUS->description_ar}}</textarea>
                                </div>
                                @error("description_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="tile-footer">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Save")}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")


@endsection
