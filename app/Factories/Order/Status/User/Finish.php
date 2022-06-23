<?php

namespace App\Factories\Order\Status\User;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;

class Finish extends OrderTypeWithUserId
{

    /**
     * @throws \Exception
     */
    public function getAll()
    {
        return $this->repository->getAllUserOrders($this->userId, 3);
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getOneUserOrder($this->userId, $orderId, 3);
    }
}
