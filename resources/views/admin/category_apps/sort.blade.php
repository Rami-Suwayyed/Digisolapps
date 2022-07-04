@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endsection
@section("css-links")
    <link rel="stylesheet" href="{{asset("assets/css/utils/sort.css")}}">
@endsection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Categories")}}</h1>
            <p>{{__("All Categories")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.category_apps.index")}}">{{__("Categories")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Sort")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")
    <form action="{{route("admin.category_apps.sort")}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-8 m-auto">
                <ul id="sortable">
                    @foreach($categories as $category)
                        <li class="ui-state-default">
                            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                            <span class="text"><i class="fas fa-sort"></i>{{$category->name}}</span>
                            <input type="hidden" name="sort[]" value="{{$category->order}}" id="Sort">
                            <input type="hidden" name="category[]" value="{{$category->id}}">
                        </li>
                    @endforeach
                </ul>
                <hr>
                <button class="btn btn-primary" >{{__("Save")}}</button>

            </div>
        </div>
    </form>

@endsection

@section("scripts")

    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset("assets/js/plugins/jquery.dataTables.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("assets/js/plugins/dataTables.bootstrap.min.js")}}"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script>
        $( function() {
            $( "#sortable" ).sortable({
                update: function( event, ui ) {
                    let count = 1
                    $("#sortable li input#Sort").each((index, el) => {
                       $(el).val(count)
                        count++;
                    })
                }
            });
        } );
    </script>

@endsection
