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
        <div class="section">
            <h3>{{__('Teacher')}}</h3>
        </div>
        <div class="section">
            <h6>{{__('Classe')}} -: {{$order->id}}</h6>
        </div>
        <div class="section">
            <div class="information">
                <h3 style="text-align: center; padding: 0; margin: 0 0 10px">{{__("Student")}}</h3>
                <div class="head">{{__('Student - name')}}:</div>
                <div class="value">{{$order->user->full_name}}</div>
            </div>
            <div class="information">
                <div class="head">{{__('Student - PhoneNumber')}}:</div>
                <div class="value">{{$order->user->student->phone_number}}</div>
            </div>
            <div class="information">
                <div class="head">{{__('Student - Email')}}:</div>
                <div class="value">{{$order->user->email}}</div>
            </div>
        </div>
        <div class="section"style="text-align: center">
            <div class="information">
                <div class="head t">{{__('Teacher - name')}}:</div>
                <div class="value">{{$order->userT->full_name}}</div>
            </div>
            <div class="information">
                <div class="head">{{__('Teacher - PhoneNumber')}}:</div>
                <div class="value"> {{$order->userT->teacher->phone_number_1}}</div>
            </div>
            <div class="information">
                <div class="head">{{__('Teacher - Email')}}:</div>
                <div class="value">{{$order->userT->email}}</div>
            </div>
        </div>
        <hr>
    </div>
</section>
<section class="table">
    <div class="container">
        <table>
            <tr>
                <th>{{__('Classe')}} -: {{$order->id}} </th>
            </tr>
        </table>
    </div>
</section>
<section class="table">
    <div class="container">
        <h3 style="text-align: center; padding: 0;">{{__('Student')}}</h3>
        <div  class="section"style="text-align: center">
            <h6 >{{__('Full Name')}} :  {{$order->user->full_name}}</h6>
            <h6 >{{__('Phone Number')}} : {{$order->user->student->phone_number}}</h6>
            <h6 >{{__('Email')}} : {{$order->user->email}}</h6>
            <h5>{{__('Image')}}</h5>
            <h6 class="text-center">
                @if($order->user->getFirstMediaFile('profile_photo'))
                    <img class="card-img-top"  style="height: 100px; width: 100px" src="{{ $order->user->getFirstMediaFile('profile_photo')->url}}" alt="Card image cap">
                @endif
            </h6>
        </div>
    </div>
    <div class="container">
        <h3 style="text-align: center; padding: 0;">{{__('Teacher')}}</h3>
        <div  class="section"style="text-align: center">
            <h6>{{__('Full Name')}} :  {{$order->userT->full_name}}</h6>
            <h6>{{__('Phone Number')}} : {{$order->userT->teacher->phone_number_1}}</h6>
            <h6>{{__('Email')}} : {{$order->userT->email}}</h6>
{{--            <h5>{{__('Image')}}</h5>--}}
                @if($order->userT->getFirstMediaFile('profile_photo'))
                    <img class="card-img-top"  style="height: 100px; width: 100px" src="{{ $order->userT->getFirstMediaFile('profile_photo')->url}}" alt="Card image cap">
                @endif
        </div>
    </div>
        <br> <br>
    <div class="container">
        <h3 style="text-align: center; padding: 0;">{{__('Subject')}}</h3>
        <div  class="section"style="text-align: center">
            @foreach($order->orderSubjects as $subject)
                <h5>{{__('Study class')}} :  {{$subject->subject->subCategory->mainCategory->getNameAttribute()}}</h5>
                <h5>{{__('grade level')}} :  {{$subject->subject->subCategory->getNameAttribute()}}</h5>
                <h5>{{__('Subject')}} : </h5>
                <h6>{{__('Subject Name')}} :  {{$subject->subject->getNameAttribute()}}</h6>
                <h6>{{__('Subject Image')}} : </h6>
                <h6 class=" text-center">
                    @if($subject->subject->getFirstMediaFile())
                        <img class="card-img-top"  style="height: 200px; width: 200px" src="{{ $subject->subject->getFirstMediaFile()->url}}" alt="Card image cap">
                    @endif
                </h6>
            @endforeach
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="section" style="width: 100%; float: right">
{{--            <div class="total-box">--}}
{{--                <div class="head">Test</div>--}}
{{--                <div class="value">775472</div>--}}
{{--            </div>--}}
{{--            <div  class="total-box">--}}
{{--                <div class="head">Test</div>--}}
{{--                <div class="value">%45475</div>--}}
{{--            </div>--}}
{{--            <div  class="total-box">--}}
{{--                <div class="head">Test</div>--}}
{{--                <div class="value">542452</div>--}}
{{--            </div>--}}
{{--            <div  class="total-box">--}}
{{--                <div class="head">Test</div>--}}
{{--                <div class="value">58254</div>--}}
{{--            </div>--}}
            <div  class="total-box total-amount">
                <div class="head">Test</div>
                <div class="value">5423542</div>
            </div>
        </div>
    </div>

</footer>

</body>
</html>
