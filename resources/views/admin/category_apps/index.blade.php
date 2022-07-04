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
            <li class="breadcrumb-item"><a href="#">{{__("Categories")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="buttons-group">
                <a href="{{route("admin.category_apps.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create Category App')}}</a>
                <a href="{{route("admin.category_apps.index", ["page" => "sort"])}}" class="btn btn-primary"><i class="fas fa-sort"></i> {{__('Sort')}}</a>
            </div>
               <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive" id="purchase_order">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("ID")}}</th>
                                <th>{{__("Image")}}</th>
                                <th>{{__("Name")}}</th>
                                <th>{{__("Activation")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{$category->id}}</td>
                                    <td>"hffgff"</td>
                                    <td>{{$category->name}}</td>

                                    @if(isPermissionsAllowed("control-students"))
                                        <td>
                                            <div class="toggle-flip changes-status">
                                                <label>
                                                    <input type="checkbox" class="changes-status"  data-url="{{route("ajax.categories.change_status")}}" id="status{{$category->id}}}" data-status-type="user" data-id="{{$category->id}}" {{checked("status", true, $category)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                                </label>
                                            </div>
                                        </td>
                                    @else
                                        <td>
                                            {{__("don't permissions")}}
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{route("admin.category_apps.edit", $category->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.category_apps.destroy", $category->id)}}" method="post" id="delete{{$category->id}}" style="display: none" data-swal-title="{{__("Delete Category")}}" data-swal-text="{{__("Are You Sure To Delete This Category?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the category has been deleted succssfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$category->id}}"><i class="far fa-trash-alt"></i></span>
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
    <script type="module" src="{{asset("assets/js/pages/teachers.js")}}"></script>

@endsection
