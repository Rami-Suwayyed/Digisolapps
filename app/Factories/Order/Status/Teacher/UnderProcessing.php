<?php

namespace App\Factories\Order\Status\Teacher;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;
use App\Http\Resources\Teacher\OrderResource;

class UnderProcessing extends OrderTypeWithUserId
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

        return OrderResource::collection($this->repository->getAllTeacherAcceptOrders($this->userId, [1,2]));
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {

        return $this->repository->getTeacherAcceptOrderById($orderId, $this->userId, [1, 2]);
    }
}
