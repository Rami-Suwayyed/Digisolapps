@extends("layouts.admin.app")
@section("page-title")
    {{__("Terms and Conditions")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Terms and Conditions")}}</h1>
            <p>{{__("All Terms and Conditions")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.terms.index")}}">{{__("Terms and Conditions")}}</a></li>
{{--            <li class="breadcrumb-item"><a href="#">{{__("Staff")}}</a></li>--}}
{{--            <li class="breadcrumb-item"><a href="#">{{$leader->full_name}}</a></li>--}}
            <li class="breadcrumb-item"><a href="#">{{__("Terms and Conditions")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
        </ul>
    </div>
@endsection

@section("css-links")
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        #ServicesSelectedBox { display: flex; margin: 15px 10px; flex-wrap: wrap}
        #ServicesSelectedBox .service-item {
            display: none;
            background: var(--primary);
            padding: 5px 15px;
            color: #fff;
            border-radius: 15px;
            margin: 0 5px 8px 0;
        }
        #ServicesSelectedBox .service-item span.text{
            font-size: 17px;
            font-weight: 600;
        }
        #ServicesSelectedBox .service-item span.icon-close{
            margin-left: 7px;
            display: inline-block;
            cursor: pointer;
            font-weight: 400;
            font-size: 14px;
            transition: all .6s;
        }
        #ServicesSelectedBox .service-item span.icon-close:hover{
            color: #ededed;
            transform: rotate(180deg);
            font-weight: 700;
        }
    </style>
@endsection

@section("content")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Edit Terms and Conditions")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.terms.update", ["id" => $term->id])}}" enctype="multipart/form-data">
                        @csrf
                        @method("put")
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Terms and Conditions")}}</label>
                                    <textarea class="form-control @if($errors->has('text_en')) is-invalid @endif" cols="30" rows="10" name="text_en" placeholder="{{__("Enter English Terms and Conditions")}}" >{{inputValue("text_en", $term)}}</textarea>
                                </div>
                                @error("Terms and Conditions")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Terms and Conditions")}}</label>
                                    <textarea class="form-control @if($errors->has('text_ar')) is-invalid @endif" cols="30" rows="10" name="text_ar" placeholder="{{__("Enter Arabic Terms and Conditions")}}" >{{inputValue("text_ar", $term)}}</textarea>
                                </div>
                                @error("Terms and Conditions")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>


                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Edit")}}</button>
                        </div>
                        </div>
                    </form>
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
    <script type="module" src="{{asset("assets/js/pages/create_technician.js")}}"></script>

@endsection
