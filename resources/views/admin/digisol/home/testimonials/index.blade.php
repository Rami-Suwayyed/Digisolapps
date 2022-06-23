@extends("layouts.admin.app")
@section("page-title")
    {{__("Digisol")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("HomeTestimonial")}}</h1>
            <p>{{__("Digisol-HomeTestimonial")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Digisol")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Testimonials")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="buttons-group">
                <a href="{{route("admin.digisol.home.body.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create Our clients')}}</a>
            </div>
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("ID")}}</th>
                                <th>{{__("Name")}}</th>
                                <th>{{__("Image")}}</th>
                                <th>{{__("Body")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($testimonials as $testimonial)
                                <tr>
                                    <td>{{$testimonial->id}}</td>
                                    <td>@if($testimonial->getFirstMediaFile()) <img src="{{ $testimonial->getFirstMediaFile()->url}}" alt="" width="50px">@endif</td>
                                    <td>{{$testimonial->name}}</td>
                                    <th>{{$testimonial->body}}</th>
                                    <td>
                                        <a href="{{route("admin.digisol.home.body.edit", $testimonial->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.digisol.home.body.destroy", $testimonial->id)}}" method="post" id="delete{{$testimonial->id}}" style="display: none" data-swal-title="{{__("Delete Says HomeTestimonial")}}" data-swal-text="{{__("Are You Sure To Delete This Says HomeTestimonial?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the Says HomeTestimonial has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$testimonial->id}}"><i class="far fa-trash-alt"></i></span>
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
