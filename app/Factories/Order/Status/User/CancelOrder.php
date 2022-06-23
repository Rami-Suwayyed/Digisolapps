<?php

namespace App\Factories\Order\Status\User;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;

class CancelOrder extends OrderTypeWithUserId
{

    /**
     * @throws \Exception
     */
    public function getAll()
    {
        return $this->repository->getAllUserOrders($this->userId,  [0, 5]);
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getOneUserOrder($this->userId, $orderId, [0, 5]);
    }
}
