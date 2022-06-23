@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1> {{__("Our Visions")}}</h1>
            <p>{{__("Edit New Our Visions")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.digisol.how-we-work.index")}}">{{__("how-we-works")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
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
                <h3 class="tile-title">{{__("Edit New We Work")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.digisol.how-we-work.update", ["id" => $how_we_work->id])}}">
                        @method("put")
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Title")}}</label>
                                    <input class="form-control @if($errors->has('title_en')) is-invalid @endif" type="text" name="title_en" placeholder="{{__("Enter English Title")}}" value="{{inputValue("title_en", $how_we_work)}}">
                                </div>
                                @error("title_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Title")}}</label>
                                    <input class="form-control @if($errors->has('title_ar')) is-invalid @endif" type="text" name="title_ar" placeholder="{{__("Enter Arabic Title")}}" value="{{inputValue("title_ar", $how_we_work)}}">
                                </div>
                                @error("title_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Description")}}</label>
                                    <textarea name="description_en" class="form-control @if($errors->has('description_en')) is-invalid @endif" cols="30" rows="10" placeholder="{{__("Enter English Description")}}" >{{inputValue("description_en", $how_we_work)}}</textarea>
                                </div>
                                @error("description_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Description")}}</label>
                                    <textarea name="description_ar" class="form-control @if($errors->has('description_ar')) is-invalid @endif" cols="30" rows="10" placeholder="{{__("Enter Arabic Description")}}" >{{inputValue("description_ar", $how_we_work)}}</textarea>
                                </div>
                                @error("description_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
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
