<?php

namespace App\Services;

use App\Helpers\Notification\Types\AdminNotification;
use App\Helpers\Notification\Types\UserNotification;
use App\Models\Order;
use App\Helpers\TrackOrder\TrackOrderService;

class TrackService
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function startTrack($order){

        if($order->singleAcceptOrder != null){
            $teacher=$order->singleAcceptOrder;
        }else{
            $teacher=$order->staffAcceptOrder;
        }

        $liveLoc=$teacher->teacher->find($teacher->teacher_id)->Livelocations;
        $data=[
            "order_id"=>$order->id,
            "user_id"=>$order->user_id,
            "order_lat"=>$order->order_location_latitude,
            "order_lng"=>$order->order_location_longitude,
            "teacher_id"=>$teacher->teacher_id,
            "newLat"=>$liveLoc != null ? $liveLoc->lat :null,
            "newlng"=>$liveLoc != null ? $liveLoc->lng :null
        ];
        $track= new TrackOrderService($data);
        $track->sendRequest();
    }

    public function send_to_user($arrived){
        $notification = new UserNotification($arrived->user_id,"TeachersArrived");
        $notification->send();
    }

}
