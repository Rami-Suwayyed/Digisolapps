<?php

namespace App\Repositories;
use App\Models\AppUrl;
use App\Models\GeneralSettings;
use App\Models\LocationZone;
use App\Models\PaymentMethod;
use App\Models\ZoneConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsRepository
{

    public function save(Request $request){
        $paymentRepository = new PaymentMethodsRepository();
        foreach ($request->payment_methods as $method){
            $paymentRepository->save(PaymentMethod::findOrFail($method), isset($request->{"payment-method-" . $method}), $request->{"payment-method-photo-" . $method});
        }
        $zoneConfiguration = ZoneConfiguration::first();
        $zoneConfiguration->circle_distance = $request->circle_distance;
        $zoneConfiguration->gold_circle_time = $request->gold_circle_time;
        $zoneConfiguration->silver_circle_time = $request->silver_circle_time;
        $zoneConfiguration->bronze_circle_time = $request->bronze_circle_time;
        $zoneConfiguration->total_circles = $request->total_circles;
        $zoneConfiguration->save();
        $this->saveGeneralSettings(GeneralSettings::first(), $request);
        $this->saveAppUrl(AppUrl::first(), $request);
        $this->saveLocationZone(LocationZone::first(), $request);
    }

    public function saveGeneralSettings($generalSettings,Request $request){
        $generalSettings->is_order_publishing = !empty($data["is_order_publishing"]);
        $generalSettings->time_pay =  $request->time_pay ??  $generalSettings->time_pay;
        $generalSettings->Time_accept =  $request->Time_accept ??  $generalSettings->Time_accept;
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

    public function saveLocationZone($Location, Request $request){
        $Location->zone_one = $request->zone_one??  $Location->zone_one;
        $Location->zone_two = $request->zone_two ??  $Location->zone_two ;
        $Location->zone_three = $request->zone_three??  $Location->zone_three;
        $Location->save();
    }
}
