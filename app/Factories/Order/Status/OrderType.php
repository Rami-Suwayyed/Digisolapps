<?php

namespace App\Factories\Order\Status;

use App\Repositories\OrderRepository;

abstract class OrderType
{
    protected OrderRepository $repository;
    protected $data;
    protected int $statusNumber;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }


    abstract public function getAll();
    abstract public function getOneDetails($orderId);


}
