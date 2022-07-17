@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Website Services marketing")}}</h1>
            <p>{{__("")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Digisol")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Services")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="buttons-group">
               <a href="{{route("admin.digisol.Services.market.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create Services marketing')}}</a>
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
                            @foreach($markets as $market)
                                <tr>
                                    <td>{{$market->id}}</td>
                                    <td>{{$market->getTitleAttribute()}}</td>
                                    <th>{{$market->getDescriptionAttribute()}}</th>
                                    <td>
                                        <a href="{{route("admin.digisol.Services.market.edit", ["id" => $market->id])}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.digisol.Services.market.destroy", $market->id)}}" method="post" id="delete{{$market->id}}" style="display: none" data-swal-title="{{__("Delete How To Order")}}" data-swal-text="{{__("Are Your Sure To Delete This How To Order ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__('the How To Order has been deleted successfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$market->id}}"><i class="far fa-trash-alt"></i></span>
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
