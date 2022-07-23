@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Settings")}}</h1>
            <p>{{__("Settings")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Settings")}}</a></li>
        </ul>
    </div>
@endsection
@section("css-links")
    <link rel="stylesheet" href="{{asset("assets/css/" . app()->getLocale() . "/pages/settings.css")}}">
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <form action="{{route("admin.KadyTech.settings.save")}}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="col-lg-4 settings-section ">
                                <h3 class="text-center"><span class="d-inline-block border-bottom pb-2 pl-3 pr-3">{{__('General Settings')}}</span></h3>
                                <div class="col-12 pt-3">
                                    <div class="form-group">
                                        <label for=""> {{__('Whatsapp')}}</label>
                                        <input type="text" name="whatsapp" class="form-control" value="{{$general->whatsapp}}"/>
                                    </div>
                                    <div class="form-group">
                                        <label for=""> {{__('SoS_whatsapp')}}</label>
                                        <input type="text" name="SoS_whatsapp" class="form-control" value="{{$general->SoS_whatsapp}}"/>
                                    </div>
                                    <div class="form-group">
                                        <label for=""> {{__('SoS_Phone')}}</label>
                                        <input type="text" name="SoS_Phone" class="form-control" value="{{$general->SoS_Phone}}"/>
                                    </div>
                                </div>
                            </div>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{(__("Save"))}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")



@endsection
