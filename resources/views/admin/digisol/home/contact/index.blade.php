@extends("layouts.admin.app")
@section("page-title")
    {{__("Terms and Conditions")}}
@endSection

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>  {{__('Website Contact')}}</h1>
            <p>{{__('Control and view All Website Contact')}}</p>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__('Website Contact')}}</a></li>
        </ul>
    </div>
@endsection
@section("content")


    <div class="row">
        <div class="col-md-12">
{{--            <div class="buttons-group">--}}
{{--                <a href="{{route("admin.digisol.contact.create")}}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('Create Website Description')}}</a>--}}
{{--            </div>--}}
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>#{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Phone number ')}}</th>
                                <th>{{__('message')}}</th>
                                <th>{{__('Created at')}}</th>
                                <th>{{__('Control')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contacts as $contact)
                                <tr>
                                    <td>{{$contact->id}}</td>
                                    <td>{{$contact->name}}</td>
                                    <td>{{$contact->email}}</td>
                                    <td>{{$contact->phone}}</td>
                                    <td>{{$contact->description}}</td>
                                    <td>{{$contact->created_at->diffForHumans()}}</td>
                                    <td>
                                        <form action="{{route("admin.digisol.contact.destroy", ["id" => $contact->id])}}" method="post" id="delete{{$contact->id}}" style="display: none" data-swal-title="{{__('Delete Website Descriptions')}}" data-swal-text="{{__('Are Your Sure To Delete This Website Description ?')}}" data-yes="{{__('Yes')}}" data-no="{{__('No')}}" data-success-msg="{{__('the Website Descriptions has been deleted succssfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$contact->id}}"><i class="far fa-trash-alt"></i></span>
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
