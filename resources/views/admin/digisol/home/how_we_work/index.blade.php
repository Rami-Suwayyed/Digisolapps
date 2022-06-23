@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Website How We Work")}}</h1>
            <p>{{__("All  How We Work")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.dashboard.index")}}">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("We Work")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="buttons-group">
                <a href="{{route("admin.digisol.how-we-work.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create How We Work')}}</a>
            </div>
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("ID")}}</th>
                                <th>{{__("Title")}}</th>
                                <th>{{__("Description")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ourVisions as $ourVision)
                                <tr>
                                    <td>{{$ourVision->id}}</td>
                                    <td>{{$ourVision->getTitleAttribute()}}</td>
                                    <th>{{$ourVision->getDescriptionAttribute()}}</th>
                                    <td>
                                        <a href="{{route("admin.digisol.how-we-work.edit", ["id" => $ourVision->id])}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.digisol.how-we-work.destroy", $ourVision->id)}}" method="post" id="delete{{$ourVision->id}}" style="display: none" data-swal-title="{{__("Delete Our Vision")}}" data-swal-text="{{__("Are Your Sure To Delete This Our Vision ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__('the our vision has been deleted successfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$ourVision->id}}"><i class="far fa-trash-alt"></i></span>
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
