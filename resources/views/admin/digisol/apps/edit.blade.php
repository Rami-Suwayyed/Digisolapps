@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1> {{__("Apps")}}</h1>
            <p>{{__("Edit App")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.digisol.apps.index")}}">{{__("Apps")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{$app->getNameAttribute()}}</a></li>
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
                <h3 class="tile-title">{{__("Edit App")}}</h3>
                <div class="tile-body">
                    <form method="post"  action="{{route("admin.digisol.apps.update", ["id" => $app->id])}}" enctype="multipart/form-data">
                        @method("put")
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Name")}}</label>
                                    <input class="form-control @if($errors->has('name_en')) is-invalid @endif" type="text" name="name_en" placeholder="{{__("Enter English Name")}}" value="{{inputValue("name_en", $app)}}">
                                </div>
                                @error("name_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Name")}}</label>
                                    <input class="form-control @if($errors->has('name_ar')) is-invalid @endif" type="text" name="name_ar" placeholder="{{__("Enter Arabic Name")}}" value="{{inputValue("name_ar", $app)}}">
                                </div>
                                @error("name_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Description")}}</label>
                                    <textarea name="description_en" class="form-control @if($errors->has('description_en')) is-invalid @endif" cols="30" rows="10" placeholder="{{__("Enter English Description")}}" >{{inputValue("description_en", $app)}}</textarea>
                                </div>
                                @error("description_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Description")}}</label>
                                    <textarea name="description_ar" class="form-control @if($errors->has('description_ar')) is-invalid @endif" cols="30" rows="10" placeholder="{{__("Enter Arabic Description")}}" >{{inputValue("description_ar", $app)}}</textarea>
                                </div>
                                @error("description_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label"><img src="{{asset($Web)}}" alt="" width="20" >{{__("Link Web")}}</label>
                                    <input class="form-control @if($errors->has('link_web')) is-invalid @endif" type="text" name="link_web" placeholder="{{__("Enter Link Web")}}" value="{{inputValue("link_web", $app)}}">
                                </div>
                                @error("link_web")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label"><img src="{{asset($Android)}}" alt="" width="20" >{{__("Link Android")}}</label>
                                    <input class="form-control @if($errors->has('link_android')) is-invalid @endif" type="text" name="link_android" placeholder="{{__("Enter Link Android")}}" value="{{inputValue("link_android", $app)}}">
                                </div>
                                @error("link_android")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label"><img src="{{asset($Ios)}}" alt="" width="20" >{{__("English Link Ios")}}</label>
                                    <input class="form-control @if($errors->has('link_ios')) is-invalid @endif" type="text" name="link_ios" placeholder="{{__("Enter Link Ios")}}" value="{{inputValue("link_ios", $app)}}">
                                </div>
                                @error("link_ios")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label"><img src="{{asset($Huawei)}}" alt="" width="20" >{{__("Link Huawei")}}</label>
                                    <input class="form-control @if($errors->has('link_huawei')) is-invalid @endif" type="text" name="link_huawei" placeholder="{{__("Enter Link Huawei")}}" value="{{inputValue("link_huawei", $app)}}">
                                </div>
                                @error("link_huawei")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("date")}}</label>
                                    <input class="form-control @if($errors->has('date')) is-invalid @endif" type="date" name="date" placeholder="{{__("Enter Date")}}" value="{{inputValue("date", $app)}}">
                                </div>
                                @error("date")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Category Apps")}}</label>
                                    <select name="category" class="custom-select form-control-border" id="exampleSelectBorder">
                                        @foreach($Categorise as $Category)
                                            <option value="{{ $Category->id }}" {{ ($Category->id == $app->category_id) ? "selected": " "}}>{{ $Category->getNameAttribute() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("data")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Icon Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" accept="image/png, image/gif, image/jpeg" data-imgs-container-class="uploaded-icon" type="file" name="icon">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("icon")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-icon">
                                    <div class="img-container">
                                        <img src="{{ $app->getFirstMediaFile('icon')->url }}" alt="{{$app->getNameAttribute()}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Background Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-background" type="file" accept="image/png, image/gif, image/jpeg" name="background">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Background")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("background")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-background">
                                    <div class="img-container">
                                        <img src="{{ $app->getFirstMediaFile('background')->url }}" alt="{{$app->getNameAttribute()}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Phone Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" accept="image/png, image/gif, image/jpeg" data-imgs-container-class="uploaded-images" type="file" name="phone">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Photo Phone")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("phone")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images">
                                    <div class="img-container">
                                        <img src="{{ $app->getFirstMediaFile('phone')->url }}" alt="{{$app->getNameAttribute()}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tile-footer">
                            <button  type="submit" class="btn btn-primary">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("update")}}
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
