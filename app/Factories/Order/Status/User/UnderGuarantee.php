<?php

namespace App\Factories\Order\Status\User;

use App\Factories\Order\Status\OrderType;
use App\Factories\Order\Status\OrderTypeWithUserId;

class UnderGuarantee extends OrderTypeWithUserId
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
        return $this->repository->getAllUserUnderGuaranteeOrders($this->userId);
    }

    /**
     * @throws \Exception
     */
    public function getOneDetails($orderId)
    {
        return $this->repository->getOneUnderGuaranteeOrder($orderId, $this->userId);
    }
}
