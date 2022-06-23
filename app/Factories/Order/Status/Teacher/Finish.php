<?php

namespace App\Factories\Order\Status\Teacher;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;
use App\Http\Resources\Teacher\OrderResource;

class Finish extends OrderTypeWithUserId
{

    /**
     * @throws \Exception
     */
    public function getAll()
    {
        return OrderResource::collection($this->repository->getAllTeacherAcceptOrders($this->userId, 3));
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getTeacherAcceptOrderById($orderId, $this->userId, 3);
    }
}
