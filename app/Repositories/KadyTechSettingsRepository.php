<?php

namespace App\Repositories;

use App\Models\KadyTechSetting;
use Illuminate\Http\Request;

class KadyTechSettingsRepository
{

    public function save(Request $request){
        $this->saveGeneralSettings(KadyTechSetting::first(), $request);
        // $this->saveAppUrl(AppUrl::first(), $request);
    }

    public function saveGeneralSettings($generalSettings,Request $request){
        $generalSettings->whatsapp = $request->whatsapp;
        $generalSettings->SoS_whatsapp = $request->SoS_whatsapp;
        $generalSettings->SoS_Phone = $request->SoS_Phone;
        $generalSettings->save();
    }

    public function saveAppUrl($appUrl, Request $request){
        $appUrl->android_url = $request->android_url;
        $appUrl->ios_url = $request->ios_url;
        $appUrl->teacher_android_url = $request->teacher_android_url;
        $appUrl->teacher_ios_url = $request->teacher_ios_url;
        $appUrl->huawei_url = $request->huawei_url;
        $appUrl->teacher_huawei_url = $request->teacher_huawei_url;
        $appUrl->save();
    }

}
