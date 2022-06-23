<?php

namespace App\Factories\Order\Status\Admin;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;

class UnderGuarantee extends OrderTypeWithUserId
{

    /**
     * @throws \Exception
     */
    public function getAll($activation = 'all')
    {
        return $this->repository->getAllOrdersUnder(3);
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getTeacherAcceptOrderById($orderId, $this->userId, 3);
    }
}
