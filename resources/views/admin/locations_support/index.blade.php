@extends("layouts.admin.app")
@section("page-title")
    {{__("Support Locations")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Support Locations")}}</h1>
            <p>{{__("Control And View All Support Locations")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Support Locations")}}</a></li>
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
                                <th>#{{__("ID")}}</th>
                                <th>{{__("Location")}}</th>
                                <th>{{__("Support")}}</th>
                                @if(hasPermissions(["edit-delivery-price", "delete-delivery-price"]))
                                <th>{{__("Control")}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($locations as $location)
                            <tr>

                                <td>{{$location->id}}</td>
                                <td>
                                    {{ $location->country .
                                     (!empty($location->governorate)    ?   " - "  . $location->governorate     : null) .
                                     (!empty($location->locality)       ?   " - "  .  $location->locality       : null) .
                                     (!empty($location->sub_locality)   ?   " - "  .  $location->sub_locality   : null) .
                                     (!empty($location->neighborhood)   ?   " - "  .  $location->neighborhood   : null)
                                    }}
                                </td>
                                <td>{{$location->support ? "Yes" : "No"}}</td>
                                @if(hasPermissions(["edit-delivery-price", "delete-delivery-price"]))
                                <td>
                                    @if(hasPermissions("edit-delivery-price"))
                                    <a href="{{route("admin.locations-support.edit", $location->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                    @endif
                                    @if(hasPermissions("delete-delivery-price"))
                                    <form action="{{route("admin.locations-support.destroy", $location->id)}}" method="post" id="delete{{$location->id}}" style="display: none" data-swal-title="{{__("Delete Location")}}" data-swal-text="{{__("Are Your Sure To Delete This Location ?")}}" data-yes="{{__("Yes")}} data-no="{{__("No")}} data-success-msg="{{__('the location has been deleted succssfully')}}">@csrf @method("delete")</form>
                                    <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$location->id}}"><i class="far fa-trash-alt"></i></span>
                                    @endif
                                </td>
                                @endif
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
@endsection
