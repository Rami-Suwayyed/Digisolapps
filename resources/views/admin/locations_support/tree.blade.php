@extends("layouts.admin.app")
@section("page-title")
    {{__("Support Locations")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("All Support Locations")}} </h1>
            <p>{{__('Control And View All Support Locations')}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__('Support Locations')}}</a></li>
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
                        <div class="tree-container">
                            {!!  buildTreeView(1,0,$treeData) !!}
                        </div>
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
    <script type="text/javascript">
    let treeview = {!! json_encode($treeData) !!};

    $(".action-tree").on("click",function(event){
        let res = $(event.target).hasClass("node-control");
        if(!$(event.target).hasClass("node-control") && !$(event.target).parent().hasClass("node-control")){
            if(!$(this).hasClass("open")){
                $(this).addClass("open").siblings("ul").slideDown();
            }else{
                $(this).removeClass("open").siblings("ul").slideUp();
            }
        }

    });

    </script>
@endsection
