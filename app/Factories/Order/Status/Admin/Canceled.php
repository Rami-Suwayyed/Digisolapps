<?php

namespace App\Factories\Order\Status\Admin;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;

class Canceled extends OrderType
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
        return $this->repository->getAllOrders(0, $activation);
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getTeacherAcceptOrderById($orderId, $this->userId, 0);
    }
}
