@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Categories")}}</h1>
            <p>{{__("All Categories Apps")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.category_apps.index")}}">{{__("Categories")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Create New  Category App")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.category_apps.store")}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Category Name")}}</label>
                                    <input class="form-control @if($errors->has('name_en')) is-invalid @endif" type="text" name="name_en" placeholder="{{__("Enter English Main Category Name")}}" value="{{inputValue("name_en")}}">
                                </div>
                                @error("name_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Category Name")}}</label>
                                    <input class="form-control @if($errors->has('name_ar')) is-invalid @endif" type="text" name="name_ar" placeholder="{{__("Enter Arabic Main Category Name")}}" value="{{inputValue("name_ar")}}">
                                </div>
                                @error("name_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Create")}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")


@endsection
