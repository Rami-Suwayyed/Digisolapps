<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class TrackOrderRepository
{

    public function getStartedOrder()
    {
//        ->where("status",2)


        return Order::where("status",2)->get();

//            ->join("order_staff_accepts","orders.id","=","order_staff_accepts.order_id")
//            ->join("users_live_location","order_single_accepts.teacher_id","=","users_live_location.user_id")

    }

    public function TeacherArrived()
    {
        return Order::where("status",4)->get();
    }

}
