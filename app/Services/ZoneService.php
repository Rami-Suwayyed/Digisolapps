<?php

namespace App\Services;

use App\Helpers\Notification\Types\AdminNotification;
use App\Helpers\Notification\Types\UserNotification;
use App\Models\Teacher;
use App\Models\Zone;
use App\Repositories\OrderTeacherRequestRepository;
use App\Repositories\ZoneRepository;
use Illuminate\Support\Facades\DB;

class ZoneService
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function start(Zone $zone){
        $zoneRepo = new ZoneRepository();
        $order = $zone->order;
        $distanceFrom = $zone->distance_per_circles * $zone->last_circle_number;
        $distanceTo = $zone->distance_per_circles * ($zone->last_circle_number + 1);
        $teacherCategory = $zone->circle_type == "g" ? 1 : ($zone->circle_type == "s" ? 2 : 3);

        $query = Teacher::select(
                    DB::raw("((ACOS(SIN( {$order->order_location_latitude} * PI() / 180) * SIN(users_live_location.lat * PI() / 180) + COS({$order->order_location_latitude}  * PI() / 180) * COS(users_live_location.lat * PI() / 180) * COS(({$order->order_location_longitude} - users_live_location.lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) * 1.6 * 1000 AS distance") ,
                    "teachers.*")
                    ->join("users_live_location", function($q){
                        $q->on("users_live_location.user_id", "teachers.user_id");
                    })
                    ->whereIn("teachers.type", ["t", "s"])
                    ->where("teachers.teacher_category", $teacherCategory)
                    ->where("teachers.accept_order", DB::raw(1))
                    ->whereNotExists(function($q) use ($order){
                        $q->select(DB::raw(1))
                            ->from('order_teachers_requests')
                            ->whereRaw('order_teachers_requests.teacher_id = teachers.user_id')
                            ->whereRaw("order_teachers_requests.order_id = {$order->id}");
                    })
                    ->whereNotExists(function($q) use ($order){
                        $q->select(DB::raw(1))
                            ->from('canceled_orders')
                            ->whereRaw('canceled_orders.teacher_id = teachers.user_id')
                            ->whereRaw("canceled_orders.order_id = {$order->id}");
                    })
                    ->havingRaw('distance BETWEEN ? AND ?', [$distanceFrom, $distanceTo]);

        foreach ($order->orderServices as $key => $orderService){
            $tableAlias = "services_teachers_$key";
            $query->join("services_teachers as " . DB::raw($tableAlias), function($q) use ($orderService, $tableAlias){
                $q->on("{$tableAlias}.teacher_id", "teachers.id");
                $q->on("{$tableAlias}.service_id", DB::raw($orderService->service_id));
            });
        }
        $query->groupBy('teachers.id');
        $teachers = $query->get();

        if($teachers->isNotEmpty()){
            foreach ($teachers as $teacher) {
                $notification = new UserNotification($teacher->user_id,"send_teacher_order");
                $notification->setTypeId($order->id);
                $notification->setStatus(1);
                $notification->send();
                (new OrderTeacherRequestRepository())->createRequest($order->id, $teacher->user_id);
            }
        }
//var_dump($zone->last_circle_number , $zone->total_circles);
        if(($zone->last_circle_number) >= $zone->total_circles){
            if($zone->circle_type === "s") {
                $zoneRepo->saveZone($zone, 0, "now", "b");
            }else if($zone->circle_type === "g"){
                $zoneRepo->saveZone($zone, $zone->last_circle_number, $zone->last_circle_time, 's');
            }else{
                $notification = new AdminNotification("order-not-assigned");
                $notification->setBodyArgs(["orderId" => $order->id])
                    ->setTitleArgs(["orderId" => $order->id, "user" => $order->user->full_name])->setUrl(route("admin.orders.show", ["id" => $order->id]));
                $notification->send();
                $zoneRepo->deleteZone($zone);
            }
        } else {
            if($zone->circle_type == 'g'){
                $zoneRepo->saveZone($zone, $zone->last_circle_number, $zone->last_circle_time, 's');
            }
            else if($zone->circle_type == 's')
                $zoneRepo->saveZone($zone, ($zone->last_circle_number + 1), "now", 'g');
            else
                $zoneRepo->saveZone($zone, ($zone->last_circle_number + 1), "now", $zone->circle_type);
        }
    }

}
