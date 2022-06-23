@extends("layouts.admin.app")
@section("page-title")
    {{__("Edit Why Sahal")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1> {{__("Why teacher")}}</h1>
            <p>{{__("Create Why teacher")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.digisol.why-teacher.index")}}">{{__("Why teacher")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create")}}</a></li>
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
                <h3 class="tile-title">{{__("Edit Why teacher")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.digisol.why-teacher.edit", ["id"=>$Whyteacher->id])}}" enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Title")}}</label>
                                    <input class="form-control @if($errors->has('title_en')) is-invalid @endif" type="text" name="title_en" placeholder="{{__("Enter English Title")}}" value="{{$Whyteacher->title_en}}">
                                </div>
                                @error("title_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Title")}}</label>
                                    <input class="form-control @if($errors->has('title_ar')) is-invalid @endif" type="text" name="title_ar" placeholder="{{__("Enter Arabic Title")}}" value="{{$Whyteacher->title_ar}}">
                                </div>
                                @error("title_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Description")}}</label>
                                    <textarea name="description_en" class="form-control @if($errors->has('description_en')) is-invalid @endif" cols="30" rows="10" placeholder="{{__("Enter English Description")}}" >{{$Whyteacher->description_en}}</textarea>
                                </div>
                                @error("description_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Description")}}</label>
                                    <textarea name="description_ar" class="form-control @if($errors->has('description_ar')) is-invalid @endif" cols="30" rows="10" placeholder="{{__("Enter Arabic Description")}}" >{{$Whyteacher->description_ar}}</textarea>
                                </div>
                                @error("description_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Image")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="image">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("image")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5 col-sm-6">
                            <div class="uploaded-images">
                                <img src="{{$Whyteacher->getFirstMediaFile()->url}}" width="75" alt="">
                            </div>
                        </div>
                        <div class="tile-footer">
                            <button  type="submit" class="btn btn-primary">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Save")}}
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
