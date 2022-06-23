<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
@include("layouts.admin.parts.header")
</head>
<body class="app sidebar-mini rtl">
<!-- Navbar-->
@include("layouts.admin.parts.navbar")

<!-- Sidebar menu-->
@include("layouts.admin.parts.slidebar")

<main class="app-content">
    @hasSection("page-nav-title")
        @yield("page-nav-title")
    @endif
    @yield("content")
</main>
@include("layouts.admin.parts.footer")
</body>
</html>
