<?php

namespace App\Helpers\Notifications\Types;

use App\Helpers\Notifications\Notification;
use App\Helpers\Notifications\Services\NotificationSender;
use Illuminate\Support\Facades\App;

class UserNotification extends Notification
{
    protected $token;
    protected $userId;

    public function __construct($userId, $token, $name){
        parent::__construct($name);
        $this->type = "user";
        $this->userId = $userId;
        $this->token = $token;

    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send()
    {

        $defultLanguage = App::getLocale();

        if($defultLanguage == "en"){
            $enTitle = __("notification.title.$this->name", $this->titleArgs["en"]);
            $enBody = __("notification.body.$this->name", $this->bodyArgs["en"]);
            App::setLocale("ar");
            $arTitle = __("notification.title.$this->name", $this->titleArgs["ar"]);
            $arBody = __("notification.body.$this->name", $this->bodyArgs["ar"]);
        }else{
            $arTitle = __("notification.title.$this->name", $this->titleArgs["ar"]);
            $arBody = __("notification.body.$this->name", $this->bodyArgs["ar"]);
            App::setLocale("en");
            $enTitle = __("notification.title.$this->name", $this->titleArgs["en"]);
            $enBody = __("notification.body.$this->name", $this->bodyArgs["en"]);
        }

        App::setLocale($defultLanguage);


        $data = [
            "registration_ids" => [$this->token],
            "notification" => [
                "title" => ${App::getLocale() . "Title"},
                "body" => ${App::getLocale() . "Body"},
                'sound' => 'mySound'
            ]
        ];

        $notificationSender = new NotificationSender($data);
        $notificationSender->sendRequest();

        $notification = new \App\Models\Notification();
        $notification->title_en = $enTitle;
        $notification->title_ar = $arTitle;
        $notification->body_en = $enBody;
        $notification->body_ar = $arBody;
        $notification->user_id = $this->userId;

        $notification->save();
        return true;
    }
}
