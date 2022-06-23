<?php

namespace App\Factories\Order\Status\User;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;

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
//        dd( $this->repository->getAllUserOrders($this->userId, [-1, 1, 2]));
        return $this->repository->getAllUserOrders($this->userId, [-1, 1, 2]);
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getOneUserOrder($this->userId, $orderId,  [-1, 1, 2]);
    }
}
