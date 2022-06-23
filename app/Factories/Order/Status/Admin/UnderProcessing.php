<?php

namespace App\Factories\Order\Status\Admin;

use App\Factories\Order\Status\OrderType;

class UnderProcessing extends OrderType
{

    public function __construct($repository)
    {
        parent::__construct($repository);
        $this->statusNumber = -1;
    }

    /**
     * @throws \Exception
     */
    public function getAll($activation = 'all')
    {
        return $this->repository->getAllOrders([1,2], $activation);
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getOrderById($orderId, [1, 2]);
    }
}
