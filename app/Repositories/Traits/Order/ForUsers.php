<?php

namespace App\Repositories\Traits\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

trait ForUsers
{



    /**
     * @throws \Exception
     */
    public function getAllUserOrders($userId, $status){
//        dd("dfdsf");
        $orderQuery = Order::where("user_id", $userId);
        $this->ordersStatusQuery($orderQuery, $status);
//        dd($orderQuery->get());
        return $orderQuery->get();
    }

    /**
     * @throws \Exception
     */
    public function getOneUserOrder($userId, $orderId, $status){
        $orderQuery = Order::where("user_id", $userId)->where("id", $orderId);
        $this->ordersStatusQuery($orderQuery, $status);
        return $orderQuery->first();
    }

    /**
     * @throws \Exception
     */
    public function getAllUserUnderGuaranteeOrders($userId)
    {
        $orderQuery = Order::where("user_id", $userId);
        $this->ordersStatusQuery($orderQuery, 3);
        $orderQuery->join('order_subjects', function ($q){
            $q->on('order_subjects.order_id', '=', 'orders.id');
            $q->on('order_subjects.guarantee_days', '!=', DB::raw(0));
            $q->on('order_subjects.guarantee_days', '>', DB::raw('TIMESTAMPDIFF(DAY, orders.finish_at, NOW())'));
        })->groupBy("orders.id");
        return $orderQuery->get(['orders.*', DB::raw('COUNT(`order_subjects`.`id`) as subject_guarantee_count')]);
    }

    /**
     * @throws \Exception
     */
    public function getOneUnderGuaranteeOrder($orderId, $userId){

        $orderQuery = Order::where("user_id", $userId)->where("orders.id", $orderId);

        $this->ordersStatusQuery($orderQuery, 3);

        $orderQuery->join('order_subjects', function ($q){
            $q->on('order_subjects.order_id', '=', 'orders.id');
            $q->on('order_subjects.guarantee_days', '!=', DB::raw(0));
            $q->on('order_subjects.guarantee_days', '>', DB::raw('TIMESTAMPDIFF(DAY, orders.finish_at, NOW())'));
        })->groupBy("orders.id");

        return $orderQuery->first(['orders.*', DB::raw('COUNT(`order_subjects`.`id`) as subject_guarantee_count')]);
    }
}
