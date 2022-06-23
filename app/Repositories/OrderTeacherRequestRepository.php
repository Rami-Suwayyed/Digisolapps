<?php

namespace App\Repositories;

use App\Models\OrderTeacherRequest;

class OrderTeacherRequestRepository
{

    public function createRequest($orderId, $teacherId){
        $orderRequest = new OrderTeacherRequest();
        $orderRequest->order_id = $orderId;
        $orderRequest->teacher_id = $teacherId;
        $orderRequest->save();
    }
}
