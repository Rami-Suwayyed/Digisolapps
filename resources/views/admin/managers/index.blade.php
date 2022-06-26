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
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")
    @if(session()->has("Manager_register_info"))
        @include("admin.managers.register_info", ["manager" => session()->get("Manager_register_info")])
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="buttons-group">
                        <a href="{{route("admin.managers.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__("Create Manager")}}</a>
                    </div>
                </div>
            </div>
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive" id="purchase_order">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>#{{__("ID")}}</th>
                                <th>{{__("Full Name")}}</th>
                                <th>{{__("Username")}}</th>
                                <th>{{__("Role")}}</th>
                                <th>{{__("Email")}}</th>
                                <th>{{__("Email Verified")}}</th>
                                <th>{{__("Activation")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($managers as $manager)
                                <tr>

                                    <td>{{$manager->id}}</td>
                                    <td>{{$manager->full_name}}</td>
                                    <td>{{$manager->username}}</td>
                                    <td>{{$manager->role->name}}</td>
                                    <td>{{$manager->email}}</td>
                                    <td>@if(!empty($manager->email_verified_at))
                                            <span class="badge badge-success">{{__('activated')}}</span>
                                        @else
                                            <a href="{{route("admin.SendEmail", ["id"=>$manager->id])}}" class="btn btn-danger"><i class="fa fa-envelope"></i> {{__("Activation")}}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="toggle-flip changes-status">
                                            <label>
                                                <input type="checkbox" class="changes-status"  data-url="{{route("ajax.manager.change_status")}}" id="status{{$manager->id}}}" data-status-type="manager" data-id="{{$manager->id}}" {{checked("status", true, $manager)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{route("admin.managers.edit", $manager->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.managers.destroy", ["manager" => $manager->id])}}" method="post" id="delete{{$manager->id}}" style="display: none" data-swal-title="{{__("Delete Teacher")}}" data-swal-text="{{__("Are You Sure To Delete This Teacher?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the teacher has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$manager->id}}"><i class="far fa-trash-alt"></i></span>
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
    @if(session()->has("Manager_register_info"))
        <script>
            $("#Register").modal('show')
        </script>
    @endif
    <!-- Google analytics script-->
    <script type="text/javascript">
        if(document.location.hostname == 'pratikborsadiya.in') {
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-72504830-1', 'auto');
            ga('send', 'pageview');
        }
    </script>
    <script type="text/javascript">
        $(".download-excel").click(function (e){
            e.preventDefault();

            let status = $("#status-filter option:selected").data("value");


            $(this).siblings("input[name='status']").val(status);


            $("#form-excel").submit();

        });
    </script>
    <script type="module" src="{{asset("assets/js/pages/managers.js")}}"></script>
@endsection
