@extends("layouts.admin.app")
@section("page-title")
    {{__("Digisol")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("first Paragraph")}}</h1>
            <p>{{__("Digisol-About first Paragraph")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-about fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Digisol")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("About")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            @if($firsts->count()!=1)
                <div class="buttons-group">
                    <a href="{{route("admin.digisol.about.first.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create first Paragraph')}}</a>
                </div>
            @endif
            <div class="tile">
                <div class="tile-first">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center">
                            <thead>
                            <tr>
                                <th>{{__("Title")}}</th>
                                <th>{{__("Description")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tfirst>
                            @foreach($firsts as $firsts)
                                <tr>
                                    <td>{{$firsts->getTitleAttribute()}}</td>
                                    <th>{{$firsts->getDescriptionAttribute()}}</th>
                                    <td>
                                        <a href="{{route("admin.digisol.about.first.edit", $firsts->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.digisol.about.first.destroy", $firsts->id)}}" method="post" id="delete{{$firsts->id}}" style="display: none" data-swal-title="{{__("Delete Says HomeTestimonial")}}" data-swal-text="{{__("Are You Sure To Delete This Says HomeTestimonial?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the Says HomeTestimonial has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$firsts->id}}"><i class="far fa-trash-alt"></i></span>
                                    </td>
                                </tr>
                            @endforeach
                            </tfirst>
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
