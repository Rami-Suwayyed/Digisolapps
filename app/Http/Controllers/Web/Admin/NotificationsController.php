<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\MainCategory;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\Firebase;

class NotificationsController extends Controller
{
    use Firebase;

    public function rules(){
        return [
            "Title"=>['required'],
            "Description"=>['required'],
            "type"=>["required"]
        ];
    }


    public function ShowSendForAll()
    {
        return view("admin.Notifications.send_for_all");
    }

    public function ShowSendForCustom()
    {
        $data["users"]=User::where("device_token","!=",null)->get();
        return view("admin.Notifications.send_for_custom",$data);
    }



    public function sendToCustomService(Request $request){
        $users = User::join("teachers", "teachers.user_id", "=", "users.id")->get("users.*");
        $token =$this->getTokens($users);
        foreach ($token as $key => $user){
            $userNotification = new Notification();
            $userNotification->title_en = $request->Title;
            $userNotification->title_ar = $request->Title;
            $userNotification->body_en = $request->Description;
            $userNotification->body_ar = $request->Description;
            $userNotification->user_id = $key;
            $userNotification->save();
        }
        $token = array_values($token);
        $Title=$request->Title;
        $Description=$request->Description;
        $this->sendFirebaseNotificationCustom(["title"=>$Title,"body"=>$Description],$token);
        $message = (new SuccessMessage())->title("send notification successfully")
            ->body("notification  has been sent ");
        Dialog::flashing($message);

        return redirect()->route("admin.Notification.SendForService");
    }

    public function send(Request $request){

        $rule=$this->rules();
        $redirect = "SendForCustom";

        if($request->type){
            $rule["id"]=["required","min:1"];
        }else{
            $rule["topic"]=["required","min:1"];
            $redirect = "SendForAll";
        }

        $valid = Validator::make($request->all(),$rule);
        if($valid->fails()){
            return redirect()->route("admin.Notification.$redirect")->withInput($request->all())->withErrors($valid->errors()->messages());
        }


        if($request->type){
            $token =$this->getTokens(User::findMany($request->id));
            foreach ($token as $key => $user){
                $userNotification = new Notification();
                $userNotification->title_en = $request->Title;
                $userNotification->title_ar = $request->Title;
                $userNotification->body_en = $request->Description;
                $userNotification->body_ar = $request->Description;
                $userNotification->user_id = $key;
                $userNotification->save();
            }
            $token = array_values($token);
        }else{
            $token = $request->topic;

            switch ($request->topic){
                case "users":
                    $users = User::where("type", "u")->get();
                    break;
                case "teachers":
                    $users = User::where("type", "t")->get();
                    break;
                case "admins":
                    $users = Admin::all();
                    break;
            }
//            dd($users);
            if ($request->topic == "admins"){
                $notification = new AdminNotification();
                $notification->title_en = $request->Title;
                $notification->title_ar = $request->Title;
                $notification->body_en = $request->Description;
                $notification->body_ar = $request->Description;
                $notification->url = "https://backend.teacher.digisolapps.com/";
                $notification->save();
            }else{
                foreach ($users as $user){
                    $userNotification = new Notification();
                    $userNotification->title_en = $request->Title;
                    $userNotification->title_ar = $request->Title;
                    $userNotification->body_en = $request->Description;
                    $userNotification->body_ar = $request->Description;
                    $userNotification->user_id = $user->id;
                    $userNotification->save();
                }
            }

        }



        $Title=$request->Title;
        $Description=$request->Description;
        $this->sendFirebaseNotificationCustom(["title"=>$Title,"body"=>$Description],$token);
        if(is_array($request->id)){
            foreach ($request->id as $user_id){
                $Notifications=new Notification();
                $Notifications->title_en=$Title;
                $Notifications->body_en = $Description;
                $Notifications->title_ar = $Title;
                $Notifications->body_ar = $Description;
                $Notifications->user_id = $user_id;
                $Notifications->status = 1;

                $Notifications->save();
            }
        }



        $message = (new SuccessMessage())->title("send notification successfully")
            ->body("notification  has been sent ");
        Dialog::flashing($message);

        return redirect()->route("admin.Notification.$redirect");

    }

    public function getTokens($users){
        $data=[];
        foreach ($users as $user){
            $data[$user->id] = $user->device_token;
        }
        return $data;
    }



    public function clear()
    {
//        $notifications = AdminNotification::all();
//        foreach ($notifications as $notification){
//            $notification->delete();
//            }
        DB::table("admin_notifications")->update(["is_open" => 1]);
        return redirect()->back();

    }

    public function ShowClear(Request $request){

        $notification = AdminNotification::where('id',$request->id)->first();
        $route =$notification->url;
        $notification->is_open= 1;
        $notification->save();
        return redirect($route);
    }


}
