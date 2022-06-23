@extends("layouts.admin.app")
@section("page-title")
    {{__("Technicians")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Users")}}</h1>
            <p>{{__("All Users")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.users.index")}}">{{__("Users")}}</a></li>

        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("ID")}}</th>
                                <th>{{__("Profile Photo")}}</th>
                                <th>{{__("Name")}}</th>
                                <th>{{__("Email")}}</th>
                                <th>{{__("Gender")}}</th>
                                <th>{{__("Activation")}}</th>

                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>@if($user->getFirstMediaFile()) <img src="{{ $user->getFirstMediaFile()->url}}" alt="" width="50px">@endif</td>
                                    <td>{{$user->full_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->gender == 1 ? "Male" : "Female"}}</td>
                                    <td>{{}}</td>
                                    <td class="text-center">
                                        <span class="status-box @if($user->status) bg-active-color @else bg-non-active-color @endif">
                                            {{$user->status ? __("Active") : __("Non-Active")}}
                                        </span>
                                    </td>
                                    <td>
                                        {{--                                        <a href="{{route("admin.sub_category.edit",["main_id" => $mainCategory->id, "id" => $category->id])}}" class="control-link edit"><i class="fas fa-edit"></i></a>--}}
                                        {{--                                        <form action="{{route("admin.sub_category.destroy", ["main_id" => $mainCategory->id, "id" => $category->id])}}" method="post" id="delete{{$category->id}}" style="display: none" data-swal-title="{{__("Delete Category")}}" data-swal-text="{{__("Are You Sure To Delete This Category?")}}" data-yes="{{__("Yes")}}"" data-no="{{__("No")}}"" data-success-msg="{{__("the category has been deleted succssfully")}}">@csrf @method("delete")</form>--}}
                                        {{--                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$category->id}}"><i class="far fa-trash-alt"></i></span>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")

    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset("assets/js/plugins/jquery.dataTables.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("assets/js/plugins/dataTables.bootstrap.min.js")}}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>

@endsection
