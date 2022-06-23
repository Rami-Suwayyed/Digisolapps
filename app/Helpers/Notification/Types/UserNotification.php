<?php

namespace App\Helpers\Notification\Types;

use App\Helpers\Notification\Notification;
use App\Helpers\Notification\Services\NotificationNodeSender;
use Illuminate\Support\Facades\App;

class UserNotification extends Notification
{
    protected $userId;
    protected $typeId = 0;
    protected $statusNotification = 0;
    public function __construct($userId, $name){
        parent::__construct($name);
        $this->type = "user";
        $this->userId = $userId;
        $this->statusNotification = 0;
        $this->typeId = 0;
    }

    public function setTypeId($typeId): UserNotification
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function setStatus($status): UserNotification
    {
        $this->statusNotification = $status;
        return $this;
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
            "title" => ${App::getLocale() . "Title"},
            "body" => ${App::getLocale() . "Body"},
            "userId" => $this->userId,
            "type" => $this->type,
            "statusNotification" => $this->statusNotification,
            "typeId" => $this->typeId
        ];
        $notificationSender = new NotificationNodeSender($data);
        $notificationSender->sendRequest();

        $notification = new \App\Models\Notification();
        $notification->title_en = $enTitle;
        $notification->title_ar = $arTitle;
        $notification->body_en = $enBody;
        $notification->body_ar = $arBody;
        $notification->user_id = $this->userId;
        $notification->typeId = $this->typeId;
        $notification->status = $this->statusNotification;

        $notification->save();
        return true;
    }
}
