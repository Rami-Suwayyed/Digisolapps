<?php

namespace App\Factories\Order\Status;

abstract class OrderTypeWithUserId extends OrderType
{
    protected $userId;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }


}
