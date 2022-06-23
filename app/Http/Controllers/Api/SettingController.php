<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\AppUrl;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(){
        $setting = AppUrl::firstOrFail();
        $data["user"]['android_url'] = $setting->android_url;
        $data["user"]['ios_url'] = $setting->ios_url;
        $data['teacher']['android_url'] = $setting->teacher_android_url;
        $data['teacher']['ios_url'] = $setting->teacher_ios_url;
        return JsonResponse::data($data)->send();
    }
}
