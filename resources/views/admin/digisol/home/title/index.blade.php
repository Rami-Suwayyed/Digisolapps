@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Website Digisol Home first paragraph")}}</h1>
            <p>{{__(" Digisol Home first paragraph")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Digisol")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("first paragraph")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            @if($homeTitles->count()!=1)
            <div class="buttons-group">
               <a href="{{route("admin.digisol.home.title.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create Website Title')}}</a>
            </div>
            @endif
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center">
                            <thead>
                            <tr>
                                <th>{{__("Title")}}</th>
                                <th>{{__("Description")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($homeTitles as $homeTitle)
                                <tr>
                                    <td>{{$homeTitle->getTitleAttribute()}}</td>
                                    <th>{{$homeTitle->getDescriptionAttribute()}}</th>
                                    <td>
                                        <a href="{{route("admin.digisol.home.title.edit", ["id" => $homeTitle->id])}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.digisol.home.title.destroy", $homeTitle->id)}}" method="post" id="delete{{$homeTitle->id}}" style="display: none" data-swal-title="{{__("Delete How To Order")}}" data-swal-text="{{__("Are Your Sure To Delete This How To Order ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__('the How To Order has been deleted successfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$homeTitle->id}}"><i class="far fa-trash-alt"></i></span>
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
