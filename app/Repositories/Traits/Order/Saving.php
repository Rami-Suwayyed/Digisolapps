<?php

namespace App\Repositories\Traits\Order;

use App\Helpers\Notification\Types\AdminNotification;
use App\Helpers\Notifications\Types\UserNotification;
use App\Http\Resources\PaymentMethodResource;
use App\Models\AvailableDaysSubject;
use App\Models\AvailableTimesSubject;
use App\Models\AvailableTimesTeacher;
use App\Models\Order;
use App\Models\OrderSubject;
use App\Models\OrderSubjectNotes;
use App\Models\OrderSubjectProperty;
use App\Models\OrderSubjectQuestion;
use App\Models\PaymentMethod;
use App\Models\Rating;
use App\Models\Subject;
use App\Models\SubjectProperty;
use App\Models\Settings;
use App\Models\SharingCode;
use App\Models\Teacher;
use App\Models\TeacherWallet;
use App\Models\Timer;
use App\Models\User;
use App\Models\UserLocation;
use App\Repositories\OrderTeacherRequestRepository;
use App\Repositories\UserLocationRepository;
use App\Repositories\ZoneRepository;
use App\Rules\AfterTime;
use App\Rules\Custom\CheckLocationIsSupport;
use App\Rules\Custom\OrderSubjects;
use App\Services\ZoneService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Traits\Order\ForTeachers;
use Illuminate\Support\Facades\Http;


trait Saving
{

    public function rules(Request $request): array
    {

        $rules = [
            "order_day" => ["required", "date"],
            "order_time_from" => ["required"],
            "order_time_to" => ["required", new AfterTime($request->order_time_from)],
//            "order_location_latitude" => ["required", "numeric"],
//            "order_location_longitude" => ["required", "numeric", new CheckLocationIsSupport($request->order_location_latitude, $request->order_location_longitude)],
//            "order_location_name" => ["required"],
//            "payment_method" => ["required", "numeric", "exists:payment_methods,id"],
            "subjects" => ["required"],
            "ues_online"   => ["required", "in:1,0"],
            "subjects.*" => ["required" , new OrderSubjects($request)],
        ];

        return $rules;
    }

    public function validate(Request $request): array
    {
        $result = [];
        $valid = Validator::make($request->all(), $this->rules($request));
        if($valid->fails()){
            $result["fails"] = true;
            $result["messages"] = $valid->errors()->messages();
        }else
            $result["fails"] = false;

        return $result;
    }

    public function create(Request $request)
    {
        $categories = json_decode($request->categories, true);

        //   $categories = $request->categories;
        $firstCategory = array_shift($categories)["category"];
        $timesQuery = AvailableTimesSubject::selectBuilder()->byCategoryId($firstCategory);
        $times = [];
        $avaliableDays = AvailableDaysSubject::selectBuilder()->byCategory($firstCategory)->first();
        $days["sunday"] = (bool)$avaliableDays->sunday;
        $days["monday"] = (bool)$avaliableDays->monday;
        $days["tuesday"] = (bool)$avaliableDays->tuesday;
        $days["wednesday"] = (bool)$avaliableDays->wednesday;
        $days["thursday"] = (bool)$avaliableDays->thursday;
        $days["friday"] = (bool)$avaliableDays->friday;
        $days["saturday"] = (bool)$avaliableDays->saturday;
        $invalidSubjects = [];
        foreach ($categories as $key => $content){
            $category = $content["category"];
            $timeCloneQuery = clone $timesQuery;
            $timeCloneQuery->matchingTimeForCategory($category, "categ{$key}");
            $timesFetching = $timeCloneQuery->get(["available_times_subjects.id",
                "available_times_subjects.start_time",
                "available_times_subjects.end_time"]);
            if($timesFetching->isEmpty()){
                $invalidSubjects += $content["subjects"];
                continue;
            }
//            $teachers=User::where("type","t")->get();

            $avaliableDays = AvailableDaysSubject::selectBuilder()->byCategory($category)->first();

            $sunday = (bool)($avaliableDays->sunday && $days["sunday"]);
            $monday= (bool)($avaliableDays->monday && $days["monday"]);
            $tuesday = (bool)($avaliableDays->tuesday && $days["tuesday"]);
            $wednesday = (bool)($avaliableDays->wednesday && $days["wednesday"]);
            $thursday = (bool)($avaliableDays->thursday && $days["thursday"]);
            $friday = (bool)($avaliableDays->friday && $days["friday"]);
            $saturday = (bool)($avaliableDays->saturday && $days["saturday"]);

            if(!($sunday || $monday || $tuesday || $wednesday || $thursday || $friday || $saturday)){
                $invalidSubjects += $content["subjects"];
                continue;
            }
            $days["sunday"] = $sunday;
            $days["monday"] = $monday;
            $days["tuesday"] = $tuesday;
            $days["wednesday"] = $wednesday;
            $days["thursday"] = $thursday;
            $days["friday"] = $friday;
            $days["saturday"] = $saturday;
            $timesQuery = $timeCloneQuery;
        }


        $times = $timesQuery->get(["available_times_subjects.id",
            "available_times_subjects.start_time",
            "available_times_subjects.end_time"]);
        $data["days"] = $days;
        $data["times"] = $times;
        $data["invaild_subjects"] = Subject::selectBuilder()->byMultiId($invalidSubjects)->getters->name()->id()->get();
        $data["payment_methods"] = PaymentMethodResource::collection(PaymentMethod::selectBuilder()->available()->get());
        if(Auth::guard("api")->check())
            $data["locations"] = (new UserLocationRepository())->getUserLocations(Auth::guard("api")->user()->id);
        $data["teachers"] =User::where('type','t')->get();
        return $data;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(Request $request){
        //Save Order
        $order = new Order();
        $order->activation = 1;
        $order->user_id = Auth::user()->id;
        $this->saveOrder($order, $request);

        //Save Subjects
        foreach ($request->subjects as $subject){
            $orderSubject = new OrderSubject();
            $this->saveOrderSubject($orderSubject, $order->id, $subject);
        }

        $user_id= $order->user_id;
        $available= new AvailableTimesTeacher();
        $this->saveAavailableTeachers($available,$request);
        (new OrderTeacherRequestRepository())->createRequest($order->id, $request->teacher);
        $this->TimerTeachers($user_id);
//
//        $client=new Client();
//        $res = $client->request('POST', env('NODE_crete_URL'), [
//            'Teacher_id' =>$order->teacher_id
//        ]);

        $url =env('NODE_crete_URL')."?Teacher_id=$order->teacher_id";
        $response = Http::post($url);
    }

    public function saveOrder(Order &$order, Request $request){
        $order->order_day = $request->order_day;
        $order->order_time_from = $request->order_time_from;
        $order->order_time_to = $request->order_time_to;
        $order->ues_online =$request->ues_online;
        if($order->ues_online == 1){
            $UserLocation =UserLocation::where('user_id',$order->user_id)->first();
            $order->order_location_latitude =  $UserLocation->lat;
            $order->order_location_longitude = $UserLocation->lng;
            $order->order_location_name = $UserLocation->title;
            $order->location_id =$UserLocation->id;
//            dd("adsadsad");
        }else{
            $order->order_location_latitude = $request->order_location_latitude;
            $order->order_location_longitude = $request->order_location_longitude;
            $order->order_location_name = $request->order_location_name;
            $order->location_id = $request->location_id;
//            $UserLocation =UserLocation::where('user_id',$order->user_id)->first();
//            $order->order_location_latitude =  $UserLocation->lat;
//            $order->order_location_longitude = $UserLocation->lng;
//            $order->order_location_name = $UserLocation->title;
//            $order->location_id =$UserLocation->id;
        }
        $order->payment_method_id =3;
        $order->promocode_id = $request->promocode_id ?? Null;
        $order->use_wallet = $request->use_wallet;
        $order->teacher_id =  $request->teacher;
        $type_education =Teacher::where('user_id',$request->teacher)->first()->type_education;
        if ($type_education){
            $order->pay = 0;
        }else{
            $order->pay =1;
        }

        $save =$order->save();
        if($save == true) {
            $device_token = User::where('id',$request->teacher)->first()->device_token;
            $notification = new UserNotification($request->teacher, $device_token, "send_New_order");
            $notification->send();
            $notification = new AdminNotification("send_New_order");
            $notification->setBodyArgs(["orderId" => $order->id])
                ->setTitleArgs(["orderId" => $order->id, "user" => $order->user->full_name])->setUrl(route("admin.orders.show", ["id" => $order->id]));
            $notification->send();
        }
    }
    public function saveAavailableTeachers(AvailableTimesTeacher &$available,Request $request){

        $available->start_time= $request->order_time_from;
        $available->end_time=$request->order_time_to;
        $available->days=$request->order_day;
        $available->created_at=now();
        $available->teacher_id = $request->teacher;
        $available->save();
    }
    public function TimerTeachers($user_id){

        $Timers=Timer::where('user_id',$user_id)->get();
        foreach ( $Timers as  $Timer){
            $Timer->delete();
        }
    }


    public function saveOrderSubject(OrderSubject $orderSubject, $orderId, $subject){
        $subjectObj = Subject::find($subject["subject_id"]);
        $orderSubject->order_id = $orderId;
        $orderSubject->subject_id = $subject["subject_id"];
        $orderSubject->subject_count = $subject["counter"];
        $orderSubject->guarantee_days = $subjectObj->guarantee_days;
        $orderSubject->addition_commission = $subjectObj->addition_commission;

        $orderSubject->save();
        if(isset($subject["properties"]) && !empty($subject["properties"]))
            foreach ($subject["properties"] as $property)
                $this->saveOrderSubjectProperty($orderSubject->id, $property);

        if(isset($subject["questions"]) && !empty($subject["questions"]))
            foreach ($subject["questions"] as $question)
                $this->saveOrderSubjectQuestion($orderSubject->id, $question);

        if(isset($subject["notes"]) && !empty($subject["notes"]))
            $this->saveOrderSubjectNote($orderSubject->id, $subject["notes"]);

    }

    public function saveOrderSubjectProperty($orderSubjectId, $property){
        $subjectProperty = SubjectProperty::find($property["id"]);
        $orderSubjectProperty = new OrderSubjectProperty();
        $orderSubjectProperty->property_id = $property["id"];
        $orderSubjectProperty->order_subject_id = $orderSubjectId;
        $orderSubjectProperty->value_type = $subjectProperty->type === "SW" ? "v" : "f";
        $orderSubjectProperty->value = $property["value"];
        if($orderSubjectProperty->value_type == "f")
            $option = $subjectProperty->selectionProperty()->where("id", $property["value"])->first();
        else
            $option = $subjectProperty->switchProperty;
        $orderSubjectProperty->value_price = $option->price;
        $orderSubjectProperty->save();
    }

    public function saveOrderSubjectQuestion($orderSubjectId, $question){
        $orderSubjectQuestion = new OrderSubjectQuestion();
        $orderSubjectQuestion->order_subject_id = $orderSubjectId;
        $orderSubjectQuestion->question_id = $question["id"];
        $orderSubjectQuestion->answer = $question["answer"];
        $orderSubjectQuestion->note = $question["text"] ?? null;
        $orderSubjectQuestion->save();
    }

    public function saveOrderSubjectNote($orderSubjectId, $note){
        $orderSubjectNote = new OrderSubjectNotes();
        $orderSubjectNote->text = $note["text"] ?? null;
        $orderSubjectNote->order_subject_id = $orderSubjectId;
        $orderSubjectNote->save();
        if(isset($note["image"]) && File::isFile($note["image"]))
            $orderSubjectNote->saveMedia($note["image"], "image");
        if(isset($note["voice"]) && File::isFile($note["voice"]))
            $orderSubjectNote->saveMedia($note["voice"], "voice");
    }


    public function haversineGreatCircleDistance(Request $request ,$teachers)
    {
        $IdUser=Auth::user()->id;
        $UserLocation =UserLocation::where('user_id',$IdUser)->first();
        $timerUesrs =Timer::where('user_id',$IdUser)->get();
        $IdJ=0;
        $latitudeFrom =  $UserLocation ->lat;;//$request->latitudeUser;
        $longitudeFrom = $UserLocation ->lng;;//$request->longitudeUser
        if($timerUesrs != null){
            foreach ($timerUesrs as $timerUesr){
                $IdJ=$timerUesr->Jid;
            }
            $IdJ++;
        }
        foreach ($teachers as $teacher){
            $timers=new Timer();
//            $timers->Jid=$request->Jid;
            $timers->Jid= $IdJ;
            $timers->subject_id=$request->subject_id;
            $timers->price=$teacher->price??0;
            $timers->user_id = $IdUser;
            $timers->volunteer = $teacher->type_education;
            $timers->teacher_id=$teacher->id;
            $timers->rating = $teacher->rating->avg("number_rating")??0;
            $timers->email=$teacher->email;
            $timers->higher_education= $teacher->teacher->higher_education;
            $timers->ues_online= $request->online;
            $timers->start_time= $request->start_time;
            $timers->end_time= $request->end_time;
            $timers->created_at=now();
            $latitudeTo=$teacher->latitude;
            $longitudeTo=$teacher->longitude;
            //Calculate distance from latitude and longitude
            $theta = $longitudeFrom - $longitudeTo;
            $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $distance = ($miles * 1.609344).' km';
            $timers->distance= $distance;
            $timers->save();
        }
    }
}
