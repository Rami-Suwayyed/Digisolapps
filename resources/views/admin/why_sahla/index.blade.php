@extends("layouts.admin.app")
@section("page-title")
    {{__("Why teacher")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Why teacher")}}</h1>
            <p>{{__("All Why teacher")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Why teacher")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="buttons-group">
                <a href="{{route("admin.digisol.why-teacher.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create Why teacher')}}</a>
            </div>
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("ID")}}</th>
                                <th>{{__("Image")}}</th>
                                <th>{{__("Title")}}</th>
                                <th>{{__("Description")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Whyteacher as $teacher)
                                <tr>
                                    <td>{{$teacher->id}}</td>
                                    <td><img src="{{$teacher->getFirstMediaFile()->url}}" width="75" alt=""></td>
                                    <td>{{$teacher->getTitleAttribute()}}</td>
                                    <th>{{$teacher->getDescriptionAttribute()}}</th>
                                    <td>
                                        <a href="{{route("admin.digisol.why-teacher.edit", ["id" => $teacher->id])}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.digisol.why-teacher.destroy", $teacher->id)}}" method="post" id="delete{{$teacher->id}}" style="display: none" data-swal-title="{{__("Delete Why teacher")}}" data-swal-text="{{__("Are Your Sure To Delete This Why teacher ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__('the Why teacher has been deleted successfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$teacher->id}}"><i class="far fa-trash-alt"></i></span>
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
