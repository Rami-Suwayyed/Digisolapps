@extends("layouts.admin.app")
@section("page-title")
    {{__("Terms and Conditions")}}
@endSection

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>  {{__('Terms and Conditions')}}</h1>
            <p>{{__('Control and view All Terms and Conditions')}}</p>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__('Terms and Conditions')}}</a></li>
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
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>#{{__('ID')}}</th>
                                <th>{{__('Terms and condition in Arabic')}}</th>
                                <th>{{__('Terms and condition in English')}}</th>
                                <th>{{__('Control')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($terms as $term)
                                <tr>
                                    <td>{{$term->id}}</td>
                                    <td>{{$term->text_en}}</td>
                                    <td>{{$term->text_ar}}</td>

                                    <td>
                                        <a href="{{route("admin.terms.edit", $term->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.terms.destroy", ["id" => $term->id])}}" method="post" id="delete{{$term->id}}" style="display: none" data-swal-title="{{__('Delete Terms and Conditions')}}" data-swal-text="{{__('Are Your Sure To Delete This Term And Condition ?')}}" data-yes="{{__('Yes')}}" data-no="{{__('No')}}" data-success-msg="{{__('the Term And Condition has been deleted succssfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$term->id}}"><i class="far fa-trash-alt"></i></span>
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
{{--    @if(session()->has("technician_register_info"))--}}
{{--        <script>--}}
{{--            $("#Register").modal('show')--}}
{{--        </script>--}}
{{--    @endif--}}

@endsection
