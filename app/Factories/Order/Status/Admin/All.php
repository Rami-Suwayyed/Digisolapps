<?php

namespace App\Factories\Order\Status\Admin;

use App\Factories\Order\Status\OrderType;

class All extends OrderType
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
        return $this->repository->getAllOrders('all', $activation);
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getOrderById($orderId);
    }
}
