<?php

namespace App\Helpers\Notifications\Types;

use App\Helpers\Notification\Notification;
use App\Helpers\Notification\Services\NotificationNodeSender;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class AdminNotification extends Notification
{
    protected $url = "#";

    public function __construct($name){
        parent::__construct($name);
        $this->type = "admin";
        $this->url = "#";
    }

    public function setUrl($url): AdminNotification
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(): bool
    {
        $enTitle = __("notification.title.$this->name", $this->titleArgs["en"]);
        $enBody = __("notification.body.$this->name", $this->bodyArgs["en"]);
        $arTitle = __("notification.title.$this->name", $this->titleArgs["ar"]);
        $arBody = __("notification.body.$this->name", $this->bodyArgs["ar"]);
        $data = [
            "title" => ${App::getLocale() . "Title"},
            "body" => ${App::getLocale() . "Body"},
            "url" => $this->url,
            "type" => $this->type,
            "time" => Carbon::now()->diffForHumans()
        ];

        $notification = new \App\Models\AdminNotification();
        $notification->title_en = $enTitle;
        $notification->title_ar = $arTitle;
        $notification->body_en = $enBody;
        $notification->body_ar = $arBody;
        $notification->url = $this->url;
        $notification->save();

        $data["id"] = $notification->id;
        $notificationSender = new NotificationNodeSender($data);
        $notificationSender->sendRequest();

        return true;
    }
}
