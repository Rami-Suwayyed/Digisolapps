<?php

namespace App\Factories\Order\Status\Teacher;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;
use App\Http\Resources\Teacher\OrderResource;

class Pending extends OrderTypeWithUserId
{

    public function __construct($repository)
    {
        parent::__construct($repository);
        $this->statusNumber = -1;
    }

    /**
     * @throws \Exception
     */
    public function getAll()
    {
        $orders = $this->repository->getAllTeacherRequestOrders($this->userId, -1);
        return ["active" => OrderResource::collection($orders['active']), "in_active" => OrderResource::collection($orders['in_active'])];
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getTeacherRequestOrderById($orderId, $this->userId, -1);
    }
}
