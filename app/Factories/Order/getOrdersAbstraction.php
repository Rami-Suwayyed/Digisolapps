<?php

namespace App\Factories\Order;

use App\Factories\Order\Status\OrderType;
use App\Repositories\OrderRepository;

abstract class getOrdersAbstraction
{

    protected static $status;
    abstract protected static function create($type, OrderRepository $repository);


}
