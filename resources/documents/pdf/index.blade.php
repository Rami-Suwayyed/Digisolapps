<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta property="og:image" content="https://backend.teacher.digisolapps.com/assets/ostad-logo.svg">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__("Teacher"  )}}</title>
    <link rel="stylesheet"  href="{{asset("assets/css/" . app()->getLocale() . "/documents/pdf/invoice.css")}}">
</head>
<body>

<header>
    <div class="container">
        <div class="section width-60-percent">
            <div class="logo">
                <img src="{{asset("assets/W.png")}}" alt="">
            </div>
        </div>
        <div class="section width-40-percent text-right">
            <div class="information">
                <div class="field text font-bold" style="font-size: 16px">{{__('Teacher')}}</div>
                <div class="field text"> {{__('Teacher')}} :+9665454456</div>
                <div class="field url"><a href="https://web.sahla.digisolapps.com/">https://Teacher.com</a></div>
                <div class="field url"><a href="mailto:webmaster@example.com/">inf@Teacher.com</a></div>
            </div>
        </div>
    </div>
</header>
<section class="customer">
    <div class="container">
        <hr>
    </div>
</section>

<section class="table">
    <div class="container">
        <h3 style="text-align: center; padding: 0; margin: 0 0 10px">{{__("Classes")}}</h3>
        <table>
            <tr>
                <th>#{{__("ID")}}</th>
                <th>{{__("Student")}}</th>
                <th>{{__("Teacher")}}</th>
                <th>{{__("paid")}}</th>
                <th>{{__("Date")}}</th>
                <th>{{__("Time")}}</th>
                <th>{{__("Location")}}</th>
                <th>{{__("Status")}}</th>
                <th>{{__("Created at")}}</th>
            </tr>
            @foreach($Classes as $Classe)
                <tr>
                    <td>{{$Classe->id}}</td>
                    <td>{{$Classe->user->full_name}}</td>
                    <td>{{$Classe->userT->full_name}}</td>
                    @if($Classe->pay)
                        <td class="text-center">
                            <span class="status-box bg-active-color">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </span>
                        </td>
                    @else
                        <td class="text-center">
                            <span class="status-box bg-non-active-color">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </span>
                        </td>
                    @endif
                    <td>{{$Classe->order_day}}</td>
                    <td>{{date("H:i A", strtotime($Classe->order_time_from))}} - {{date("H:i A", strtotime($Classe->order_time_to))}}</td>
                    @if($Classe->ues_online == 0)
                        <td> <!-- Button trigger modal -->
                            {{__("View Location")}}
                        </td>
                    @else
                        <td>
                            <button class="btn btn-primary">
                                {{__("Online")}}
                            </button>
                        </td>
                    @endif
                    <td><span class="status @if($Classe->status == "0") red @elseif($Classe->status == "-1") yellow @else green @endif">{{$Classe->getStatusText()}}</span></td>
                    <td>{{$Classe->created_at->diffForHumans()}}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td data-label="name"></td>
                <td data-label="war"></td>
                <td data-label="ba"></td>
            </tr>
        </table>
    </div>
</section>



{{--<div class="page-break"></div>--}}


{{--<footer class="text-right">--}}
{{--    <div class="container">--}}
{{--        <div class="section" style="width: 40%; float: right">--}}

{{--        </div>--}}

{{--    </div>--}}

{{--</footer>--}}

</body>
</html>
