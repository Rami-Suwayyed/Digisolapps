@extends("layouts.admin.app")
@section("page-title")
{{__("Profile")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Dashboard")}}</h1>
            <p>{{__("Profile Dashboard")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Profile")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row user">

        <div class="col-md-3">
            <div class="tile p-0">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item"><a class="nav-link active" href="{{route('admin.profile.Setting')}}">Settings</a></li>
{{--                    <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Settings</a></li>--}}
{{--                    <li class="nav-item"><a class="nav-link" href="#user-Password" data-toggle="tab">Change Password</a></li>--}}
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="user-timeline">
                    <div class="timeline-post">
                        <div class="post-media"><a href="#"><img src="{{$admin->getProfilePhotoAttribute()}}" width="75" style="border-radius: 50%;"></a>
                            <div class="content">
                                <h5><a href="#">{{$admin->full_name}}</a></h5>
                                <p class="text-muted"><small>{{$admin->email}}</small></p>
                            </div>
                        </div>
                        <div class="post-content">
                            <p> </p>
                        </div>
                        <ul class="post-utility">
                            <li class="likes"><a href="#"><i class="fa fa-fw fa-lg fa-thumbs-o-up"></i>{{$admin->full_name}}</a></li>
                            <li class="shares"> <a href="#"><i class="fa fa-envelope" aria-hidden="true"></i> {{$admin->email}} </a></li>
                            <li class="comments"><i class="fa fa-fw fa-lg fa-comment-o"></i>{{$admin->username}}</li>
                        </ul>
                    </div>
                </div>
                <div class="tab-pane fade" id="user-settings">
                    <div class="tile user-settings">
                        <h4 class="line-head">Settings</h4>
                        <form action="{{route('admin.profile.update')}}" method="POST" >
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label>Full Name</label>
                                    <input class="form-control @if($errors->has('full_name')) is-invalid @endif" type="text" name="full_name" placeholder="{{__("Enter Full Name")}}" value="{{inputValue("full_name", $admin)}}">
                                    @error("full_name")
                                    <div class="input-error">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label>Username</label>
                                    <input class="form-control @if($errors->has('username')) is-invalid @endif" type="text" name="username" placeholder="{{__("Enter Username")}}" value="{{inputValue("username", $admin)}}">
                                    @error("username")
                                    <div class="input-error">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mb-4">
                                    <label>Email</label>
                                    <input class="form-control  @if($errors->has('email')) is-invalid @endif" type="text" name="email" placeholder="{{__("Enter Email")}}" value="{{inputValue("email", $admin)}}">
                                    @error("email")
                                    <div class="input-error">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>
                                        {{__("Save")}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
{{--                <div class="tab-pane fade" id="user-Password">--}}
{{--                    <div class="tile user-settings">--}}
{{--                        <h4 class="line-head">Chanage Password</h4>--}}
{{--                        <form action="{{route('admin.profile.ChangePassword')}}" method="POST" >--}}
{{--                            @csrf--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-8 mb-4">--}}
{{--                                    <label>Password</label>--}}
{{--                                    <input class="form-control  @if($errors->has('old_password')) is-invalid @endif" type="password" name="old_password" placeholder="{{__("Enter Current password")}}">--}}
{{--                                    @error("old_password")--}}
{{--                                    <div class="input-error">{{$message}}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="clearfix"></div>--}}
{{--                                <div class="col-md-8 mb-4">--}}
{{--                                    <label>New Password</label>--}}
{{--                                    <input class="form-control @if($errors->has('password')) is-invalid @endif" type="password" name="password" placeholder="{{__("Enter New password")}}">--}}
{{--                                    @error("password")--}}
{{--                                    <div class="input-error">{{$message}}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="clearfix"></div>--}}
{{--                                <div class="col-md-8 mb-4">--}}
{{--                                    <label>Confirm Password</label>--}}
{{--                                    <input class="form-control  @if($errors->has('confirm_password')) is-invalid @endif" type="password" name="confirm_password" placeholder="{{__("Enter Confirmed password")}}">--}}
{{--                                    @error("confirm_password")--}}
{{--                                    <div class="input-error">{{$message}}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row mb-10">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>--}}
{{--                                        {{__("Save")}}</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>

@endsection

@section("scripts")

@endsection
