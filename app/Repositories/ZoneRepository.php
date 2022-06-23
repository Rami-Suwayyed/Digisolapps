<?php

namespace App\Repositories;

use App\Models\Settings;
use App\Models\ZoneConfiguration;
use App\Models\Zone;
use App\Services\ZoneService;
use Illuminate\Support\Facades\DB;

class ZoneRepository
{

    public function getReadyZones(){
        return Zone::where(function ($q){
            $q->whereRaw("(CASE
                             WHEN circle_type = 's' THEN gold_time_per_circles <= TIMESTAMPDIFF(MINUTE,  last_circle_time, NOW())
                             WHEN circle_type = 'g' THEN silver_time_per_circles <= TIMESTAMPDIFF(MINUTE,  last_circle_time, NOW())
                             WHEN circle_type = 'b' THEN bronze_time_per_circles <= TIMESTAMPDIFF(MINUTE,  last_circle_time, NOW())
                            ELSE 0 END)");
        })->join("orders", function ($q){
            $q->on("orders.id", "zones.order_id");
            $q->on("orders.activation", DB::raw(1));
        })->groupBy("zones.id")->get("zones.*");
    }


    public function createZone($orderId){
        $zoneConfig = Settings::getInstance()->getZoneConfiguration();
        $zone = new Zone();
        $zone->total_circles = $zoneConfig->total_circles;
        $zone->gold_time_per_circles = $zoneConfig->gold_circle_time;
        $zone->silver_time_per_circles = $zoneConfig->silver_circle_time;
        $zone->bronze_time_per_circles = $zoneConfig->bronze_circle_time;
        $zone->distance_per_circles = $zoneConfig->circle_distance;
        $zone->order_id = $orderId;
        $this->saveZone($zone, 0, 'now', 'g');
        return $zone;
    }

    public function saveZone(Zone &$zone, $circleNumber, $circleTime = "now", $circleType = 'g'){
        $zone->last_circle_number = $circleNumber;
        $zone->last_circle_time = $circleTime == "now" ? getMySqlTimeStamp() : $circleTime;
        $zone->circle_type = $circleType;
        $zone->save();
    }

    public function deleteZone(Zone &$zone){
        $zone->delete();
    }

    public function getZoneByOrderId($orderId){
        return Zone::where("order_id", $orderId)->first();
    }

}
