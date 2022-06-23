 <meta name="description" content="Bunyan">

{{--    <link rel="icon" href="https://backend.teacher.digisolapps.com/golden_meal_backend/public/assets/logo.svg">--}}
<!-- Open Graph Meta-->

<meta property="og:type" content="website">
<meta property="og:site_name" content="Teacher">
<meta property="og:title" content="Vali - Free Bootstrap 4 admin theme">
<meta property="og:url" content="http://pratikborsadiya.in/blog/vali-admin">
<meta property="og:image" content="https://backend.teacher.digisolapps.com/assets/ostad-logo.svg">
<meta property="og:description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>
    @hasSection ('page-title')
        @yield('page-title') - {{ config("app.name") }}
    @else
        {{ config("app.name") }}
    @endif
</title>

 <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- Google fonts -->

 <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap" rel="stylesheet">

<!-- Main CSS-->
<link rel="stylesheet" type="text/css" href="{{asset("assets/css/main.css")}}">
<!--rating css--->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/lib/all.min.css')}}">

 <link rel="stylesheet" type="text/css" href="{{asset("assets/css/master.css")}}">

<link rel="stylesheet" type="text/css" href="{{asset('assets/css/' . app()->getLocale() . '/custom.css')}}">
 <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js" async></script>

 <link rel="icon" href="{{asset("assets/ostad-logo.svg")}}" >
 <!-- Google fonts -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap" rel="stylesheet" type="text/css">

 <!-- Font-icon css-->
@hasSection("css-links")
    @yield("css-links")
@endif
