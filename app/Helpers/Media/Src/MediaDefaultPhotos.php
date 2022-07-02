<?php
namespace App\Helpers\Media\Src;

use App\Models\Media;
use Illuminate\Support\Facades\File;

trait MediaDefaultPhotos {

    public static function defaultUserPhoto() : string {return env("APP_URL") . "/assets/img/user_avatar.jpg";}
    public static function defaultPostPhoto() : string {return env("APP_URL") . "/uploads/user_default.png";}
    public static function defaultWebPhoto() : string {return env("APP_URL") . "/assets/internet.svg";}
    public static function defaultAndroidPhoto() : string {return env("APP_URL") . "/assets/GooglePlayIcon.svg";}
    public static function defaultIosPhoto() : string {return env("APP_URL") . "/assets/appleIos.svg";}
    public static function defaultHuaweiPhoto() : string {return env("APP_URL") . "/assets/Huawei.svg";}


}
