<?php

namespace App\Helpers\Email\Types;

use App\Helpers\Notification\Notification;
use App\Helpers\Notification\Services\NotificationNodeSender;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class AdminEmail extends Notification
{
    protected $status = 0;

    public function __construct($name){
        parent::__construct($name);
        $this->status = 0;
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(): bool
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


        $notification = new \App\Models\SendEmail();
        $notification->title_en = $enTitle;
        $notification->title_ar = $arTitle;
        $notification->body_en = $enBody;
        $notification->body_ar = $arBody;
        $notification->status = $this->status;
        $notification->save();

        return true;
    }
}
