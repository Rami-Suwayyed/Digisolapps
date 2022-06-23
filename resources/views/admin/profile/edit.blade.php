@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Admins And Manager")}}</h1>
            <p>{{__("All Managers")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Managers")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Update Manager")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route('admin.profile.update')}}" enctype="multipart/form-data">
                        @csrf
                        @method("put")
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Full name")}}</label>
                                    <input class="form-control @if($errors->has('full_name')) is-invalid @endif" type="text" name="full_name" placeholder="{{__("Enter Full name")}}" value="{{$admin->full_name}}">
                                </div>
                                @error("full_name")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('Username')}}</label>
                                    <input class="form-control @if($errors->has('username')) is-invalid @endif" type="text" name="username" readonly placeholder="{{__("Enter Username")}}"  value="{{$admin->username}}" >
                                </div>
                                @error("username")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Email")}}</label>
                                    <input class="form-control @if($errors->has('email')) is-invalid @endif" type="text" name="email" placeholder="example : example@example.com" value="{{$admin->email}}">
                                </div>
                                @error("email")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Profile Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="profile_photo">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("profile_photo")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images" style="margin-bottom: 20px;">
                                    <div class="img-container">
                                        <img src="{{  $admin->getFirstMediaFile() ? $admin->getFirstMediaFile()->url : $admin->defaultUserPhoto() }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control  @if($errors->has('old_password')) is-invalid @endif" type="password" name="old_password" placeholder="{{__("Enter Current password")}}" autocomplete="off">
                                </div>
                                @error("old_password")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("New Password")}}</label>
                                    <input class="form-control @if($errors->has('password')) is-invalid @endif" type="password" name="password" placeholder="****" autocomplete="off">
                                </div>
                                @error("password")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Confirm Password")}}</label>
                                    <input class="form-control @if($errors->has('confirm_password')) is-invalid @endif" type="password" name="confirm_password" placeholder="****" autocomplete="off">
                                </div>
                                @error("confirm_password")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <br>
                        </div>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__('Update')}}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

@section("scripts")



@endsection
