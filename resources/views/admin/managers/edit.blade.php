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
                    <form method="post" action="{{route("admin.managers.update", ["manager" => $manager->id])}}" enctype="multipart/form-data">
                        @csrf
                        @method("put")
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Full name")}}</label>
                                    <input class="form-control @if($errors->has('full_name')) is-invalid @endif" type="text" name="full_name" placeholder="{{__("Enter Full name")}}" value="{{inputValue("full_name", $manager)}}">
                                </div>
                                @error("full_name")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('Username')}}</label>
                                    <input class="form-control @if($errors->has('username')) is-invalid @endif" type="text" name="username" placeholder="{{__("Enter Username")}}" readonly value="{{inputValue("username", $manager)}}">
                                </div>
                                @error("username")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Email")}}</label>
                                    <input class="form-control @if($errors->has('email')) is-invalid @endif" type="text" name="email" placeholder="example : example@example.com"  value="{{inputValue("email", $manager)}}">
                                </div>
                                @error("email")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="exampleSelect1" >{{__("Roles")}}</label>
                                    <select class="form-control @if($errors->has('role')) is-invalid @endif" id="NavigateList" name="role">
                                        <option value="">{{__("None")}}</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" @if(old("role") && old("role") === $role->id)  @elseif($manager->role->id === $role->id) selected @endif >{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("role")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Username")}}</label>
                                    <input class="form-control" type="text" name="username" readonly value="{{inputValue("username", $manager)}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('First Password')}}</label>
                                    <input class="form-control" type="text" name="username" readonly value="{{inputValue("first_password", $manager)}}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images" style="margin-bottom: 20px;">
                                    <div class="img-container">
                                        <img src="{{  $manager->getFirstMediaFile() ? $manager->getFirstMediaFile()->url : $manager->defaultUserPhoto() }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
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
