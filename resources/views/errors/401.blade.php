@extends("layouts.admin.app")
@section("page-title")
    401 Unauthorized
@endSection

@section("content")
    <div class="page-wrap d-flex flex-row align-items-center error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <span class="display-1 d-block font-weight-bold color-primary code">401</span>
                    <div class="mb-4 lead">{{__("You don't have any permission to visit this page")}}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")

@endsection
